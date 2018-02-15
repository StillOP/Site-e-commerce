<?php

session_start();
require_once("database.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");


if(!isset($_SESSION['pseudo'])) { echo"<script> document.location.href=\"inscription.php\"</script>"; }

$header = new header();
$footer = new footer();
$database = new database();

$header->display("Mailbox", $_SESSION);

$received = $database->select("SELECT * FROM message WHERE receiver='{$_SESSION['pseudo']}' AND receiver_statut != 'deleted' ORDER BY date DESC");

$sent = $database->select("SELECT * FROM message WHERE sender='{$_SESSION['pseudo']}' AND sender_statut != 'deleted' ORDER BY date DESC");

$unread = $database->select("SELECT message_id FROM message WHERE receiver_statut='unread' AND receiver='{$_SESSION['pseudo']}'");
if($unread[0] > 0)
{
    for($i = 1; $i <= $unread[0]; $i++)
    {
        $database->update("UPDATE message SET receiver_statut='normal' WHERE message_id='{$unread[$i]->message_id}'");
    }
}

?>
<ul class="nav nav-tabs" role="tablist" id="tab">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#received" role="tab" aria-controls="received" aria-selected="true" id="received-tab">Received</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#sent" role="tab" aria-controls="sent" aria-selected="false" id="sent-tab">Sent</a>
  </li>
</ul>

<div class="tab-content" id="tab">
    <div class="tab-pane fade show active" id="received" role="tabpanel" aria-labelledby="received-tab">
    </div>
    <div class="tab-pane fade" id="sent" role="tabpanel" aria-labelledby="sent-tab">
    </div>
</div>

<div class="modal fade" id="reply" tabindex="-1" role="dialog" aria-labelledby="replylabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="replylabel">Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form method="post" action="reply.php" id="reply-form">
                    <div class="form-group">
                        <input type="text" name="title" id="title" value="" hidden/>
                        <input type="text" name="receiver" id="receiver" value="" hidden/>
                        <textarea class="form-control" name="reply-content" id="reply-content" rows="10"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="new-description-submit" onclick="send_reply()">Reply</button>
            </div>
        </div>
    </div>
</div>

<script>
    
    function setvalue(title, receiver)
    {
        document.querySelector('#title').value = title;
        document.querySelector('#receiver').value =  receiver;
    }
    
    function send_reply()
    {
        var content = document.querySelector('#reply-content').value;
        if(content != "" && content.length <= 65000)
        {
            document.querySelector('#reply-form').submit();
        }
    }
    
    var received = '<?php echo json_encode($received); ?>';
    received = received.replace(/(\r\n|\n|\r|\x0B)/gm,"<br/>");
    received = JSON.parse(received);
    
    var sent = '<?php echo json_encode($sent); ?>';
    sent = sent.replace(/(\r\n|\n|\r|\x0B)/gm,"<br/>");
    sent = JSON.parse(sent);
    
    var received_div = document.querySelector('#received');
    var sent_div = document.querySelector('#sent');
    
    var received_container = document.createElement('div');
    received_container.setAttribute('id', 'received-container');
    var sent_container = document.createElement('div');
    sent_container.setAttribute('id', 'sent-container');
    
    received_div.appendChild(received_container);
    sent_div.appendChild(sent_container);
    
    var last_index;
    if(received[0] > 0)
    {
        for(var i = 1; i <= received[0]; i++)
        {
            var title ="'"+received[i].title+"'";
            var sender = "'"+received[i].sender+"'";
            received_container.innerHTML += '<div class="card">'+
                                                '<div class="card-header" id="heading'+i+'" style="background-color:white;">'+
                                                    '<h4 class="mb-0">'+
                                                        '<i class="material-icons" style="color:green;padding-top:10px;">mail</i><button class="btn btn-link" data-toggle="collapse" data-target="#collapse'+i+'" aria-expanded="true" aria-controls="collapse'+i+'" style="color:#212529;"><h6>'+
                                                        ' From <span style="color:#17a2b8;">'+received[i].sender+'</span>:  '+received[i].title+' '+
                                                        '</h6></button>'+'<small class="text-muted" style="font-size:10px;">'+received[i].date+'</small>'+
                                                        '<span style="display:inline-flex;"><form action="remove.php" method="post">'+
                                                            '<input type="text" name="type" value="received_message" hidden/>'+
                                                            '<input type="text" name="id" value="'+received[i].message_id+'" hidden/>'+
                                                            '<button type="submit" class="btn" style="background-color:white;"><i class="material-icons" style="color:red;font-size:14px;">delete</i></button>'+
                                                        '</form></span>'+
                                                    '</h4>'+
                                                '</div>'+
                                                '<div id="collapse'+i+'" class="collapse" aria-labelledby="heading'+i+'" data-parent="#received-container">'+
                                                    '<div class="card-body">'+
                                                        '<p style="padding-left:70px;">'+received[i].content+'</p><br/>'+
                                                        '<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#reply" onclick="setvalue('+title+', '+sender+')">Reply</button>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>';
            last_index = i;
        }
    }
    else {  received_container.innerHTML = '<p>Nothing to show!</p>'; }
    
    if(sent[0] > 0)
    {
        for(var i = 1; i <= sent[0]; i++)
        {
            var index = last_index + i;
            sent_container.innerHTML += '<div class="card">'+
                                                '<div class="card-header" id="heading'+index+'" style="background-color:white;">'+
                                                    '<h4 class="mb-0">'+
                                                        '<i class="material-icons" style="color:green;padding-top:10px;">send</i><button class="btn btn-link" data-toggle="collapse" data-target="#collapse'+index+'" aria-expanded="true" aria-controls="collapse'+index+'" style="color:#212529;"><h6>'+
                                                        ' To <span style="color:#17a2b8;">'+sent[i].receiver+'</span>:  '+sent[i].title+
                                                        '</h6></button>'+'<small class="text-muted" style="font-size:10px;">'+sent[i].date+'</small>'+
                                                        '<span style="display:inline-flex;"><form action="remove.php" method="post">'+
                                                            '<input type="text" name="type" value="sent_message" hidden/>'+
                                                            '<input type="text" name="id" value="'+sent[i].message_id+'" hidden/>'+
                                                            '<button type="submit" class="btn" style="background-color:white;"><i class="material-icons" style="color:red;font-size:14px;">delete</i></button>'+
                                                        '</form></span>'+
                                                    '</h4>'+
                                                '</div>'+
                                                '<div id="collapse'+index+'" class="collapse" aria-labelledby="heading'+index+'" data-parent="#received-container">'+
                                                    '<div class="card-body">'+
                                                        '<p style="padding-left:70px;">'+sent[i].content+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>';
        }
    }
    else {  sent_container.innerHTML = '<p>Nothing to show!</p>'; }
    
</script>


<?php

$footer->display();

?>