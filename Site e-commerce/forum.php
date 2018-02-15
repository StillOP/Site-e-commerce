<?php

session_start();
require_once("usermanager.inc.php");
require_once("form.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");

$header = new header();
$form = new form();
$footer = new footer();

$header->display("Forum", $_SESSION);

?>

<?php
if(!isset($_GET['page'])) { echo "<script>document.location.href=\"forum.php?page=1\";</script>"; }

$database = new database();
$request = "SELECT * FROM topic ORDER by last_message_date DESC";
$result = $database->select($request);
$topic_per_page = 20;
$max_page = $result[0] / $topic_per_page;
$max_page = ceil($max_page);

$pin_request = "SELECT * FROM topic WHERE statut='pin' ORDER by topic_id DESC";
$pin_result = $database->select($pin_request);

if(isset($_GET['page']))
{
    //if($_GET['page'] < 1  || $_GET['page'] > $max_page) { echo "<script>document.location.href=\"forum.php?page=1\";</script>"; }
}

$nbs = [];
for($i = 1; $i <= $result[0]; $i++)
{
    $nb_request = "SELECT response_id FROM response WHERE topic_id='{$result[$i]->topic_id}' ORDER by date DESC";
    $nb_result = $database->select($nb_request);
    $nbs[] = $nb_result[0] - 1;
}

$pin_nbs = [];
for($i = 1; $i <= $pin_result[0]; $i++)
{
    $nb_pin_request = "SELECT response_id FROM response WHERE topic_id='{$pin_result[$i]->topic_id}' ORDER by date DESC";
    $nb_pin_result = $database->select($nb_pin_request);
    $pin_nbs[] = $nb_pin_result[0] - 1;
}

$statut = [];
for($i = 1; $i <= $result[0]; $i++)
{
    $statut_request = "SELECT statut FROM user WHERE pseudo='{$result[$i]->owner}'";
    $statut_result = $database->select($statut_request);
    $statut[] = $statut_result[1]->statut;
}

$pin_statut = [];
for($i = 1; $i <= $pin_result[0]; $i++)
{
    $pin_statut_request = "SELECT statut FROM user WHERE pseudo='{$pin_result[$i]->owner}'";
    $pin_statut_result = $database->select($pin_statut_request);
    $pin_statut[] = $pin_statut_result[1]->statut;
}

?>

<div class="col-xs-12">
    <div class="col-md-9" style="padding:20px;"><br/><br/>
        <h2>Forum</h2>
        <br/>
         <form class="form-inline my-2 my-lg-0" method="get" action="search.php">
             <input class="form-control mr-sm-2" type="search" name="forum_key" placeholder="Search" aria-label="Search" style="width:400px;"/>
             <button class="btn my-2 my-sm-0" type="submit" style="background-color:white;color:rgba(0,0,0,.5);">
                 <i class="material-icons">search</i>
             </button>
        </form>
        <br/>
        <br/>
        <div id="topics"></div>
        <div class="row">
            <div class="col-md-6" id="btn-div1" style="text-align:left;"><button class="btn" id="prev" style="background-color:white;"><h4><strong><</strong></h4></button></div>
                <div class="col-md-6" id="btn-div2" style="text-align:right;"><button class="btn" id="next" style="background-color:white;"><h4><strong>></strong></h4></button></div>
        </div>
        <br/>
        <hr style="border-width:2px;">
        <br/>
        <h4>New topic</h4>
        <br/>
         <form action="topic-validation.php?page=<?php echo $_GET['page']; ?>" method="post" enctype="application/x-www-form-urlencoded" id="topic-form">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" />
                </div>
                <br/>
                <div class="form-group">
                      <div>
                        <button type="button" class="btn" style="background-color:white;border:none;" onclick="input_style('bold', '#message')"><b>Bold</b></button>
                        <button type="button" class="btn" style="background-color:white;border:none;" onclick="input_style('italic', '#message')"><i>Italic</i></button>
                        <button type="button" class="btn" style="background-color:white;border:none;" onclick="input_style('quote', '#message')">Quote</button>
                        <button type="button" class="btn" style="background-color:white;border:none;" onclick="input_style('link', '#message')">Link</button>
                    </div>
                    <textarea class="form-control" id="message" name="message" rows="10"></textarea>
                </div>
                <button type="button" class="btn btn-info" id="submit-btn">POST</button>
            </form>
    </div>
    <div class="col-md-3" id="pub">
    </div>
</div>

<script>
    function formatting(text)
    {
        var regrex = [/\[bold\](.+)\[\/bold\]/g, /\[italic\](.+)\[\/italic\]/g, /\[quote\](.+)\[\/quote\]/g, /\[link\](.+)\[\/link\]/g];
        var by = ["<b>$1</b>", "<i>$1</i>", "<p style=\"background-color:#f8f9fa;color:gray;padding:10px;\">$1</p>", "<a href=\"$1\" target=\"_black\">$1</a>"];
        
        for(var i = 0; i < regrex.length; i++)
        {
            text = text.replace(regrex[i], by[i]);
        }
        return text;
    }
    var topics = document.querySelector('#topics');
    var div = document.createElement('div');
    topics.appendChild(div);
    var page = <?php echo $_GET['page']; ?>;
    var topic_per_page = <?php echo $topic_per_page; ?>;
    var request_count = <?php echo $result[0]; ?>;
    var max_page = Math.ceil(request_count / topic_per_page);
    var result = '<?php echo json_encode($result); ?>';
    var obj = JSON.parse(result);
    
    var pin_topic = <?php echo $pin_result[0]; ?>;
    var pin = '<?php echo json_encode($pin_result); ?>';
    var pin_obj = JSON.parse(pin);
    
    var last_topic_id = page * topic_per_page;
    var first_topic_id = last_topic_id - 19;
    var html = '<table class="table table-sm table-hover">'+
                    '<thead>'+
                        '<tr>'+
                            '<th scope="col"><h5>TOPIC</h5></th>'+
                            '<th scope="col"><h5>AUTHOR</h5></th>'+
                            '<th scope="col"><h5>NB</h5></th>'+
                            '<th scope="col"><h5>LAST POST</h5></th>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>';
    
    if(pin_topic != 0 && page == 1)
    {
        var pin_nbs = <?php echo json_encode($pin_nbs); ?>;
        var pin_colors = <?php echo json_encode($pin_statut); ?>;
        
        for(var i = 1; i <= pin_topic; i++)
        {
            var p_style;
            if(pin_colors[i - 1]=="admin") { p_style="color:#dc3545;" }
            else { p_style="color:#007bff;" }
            
            html += '<tr><th scope="row"><span style="display:inline-flex;"><h6 style="color:#3cb371;"><i class="material-icons">question_answer</i> &nbsp;</h6><a href="topic.php?id=';
            html += pin_obj[i].topic_id;
            html += '&title=';
            html += pin_obj[i].title;
            html += '&topic_page=1';
            html += '">';
            html += '<h6 style="color:#212529">';
            html += pin_obj[i].title;
            html += '</h6></a></span>';
            html += '</th><td>';
            html += '<a href="profil.php?pseudo='+pin_obj[i].owner+'" style="';
            html += p_style;
            html += '"><h6>';
            html += pin_obj[i].owner;
            html += '</h6></a>';
            html += '</td>';
            html += '<td>';
            html += '<h6 style="color:#868e96">'+pin_nbs[i - 1]+'<h6>';
            html += '</td>';
            html += '<td>';
            html += '<span><h6 style="color:#868e96">';
            html += pin_obj[i].last_message_date;
            html += '</h6></span>';
            html += '</td>';
            html+= '</tr>';
        }
    }
    
    if( request_count != 0)
    {
        var nbs = <?php echo json_encode($nbs); ?>;
        var colors = <?php echo json_encode($statut); ?>;
        
        for(var i = first_topic_id; i <= last_topic_id; i++)
        {
            if(obj[i].statut == 'pin') { continue; }
            
            var style;
            if(nbs[i -1] >= 20) { style="color:red;"; }
            else { style="color:#51acc7"; }
            
            var p_style;
            if(colors[i - 1]=="admin") { p_style="color:#dc3545;" }
            else { p_style="color:#007bff;" }
            
            html += '<tr><th scope="row"><span style="display:inline-flex;';
            html += style;
            html += '">';
            html += '<h6><i class="material-icons">question_answer</i> &nbsp;</h6><a href="topic.php?id=';
            html += obj[i].topic_id;
            html += '&title=';
            html += obj[i].title;
            html += '&topic_page=1';
            html += '">';
            html += '<h6 style="color:#212529">';
            html += obj[i].title;
            html += '</h6></a></span>';
            html += '</th><td>';
            html += '<a href="profil.php?pseudo='+obj[i].owner+'" style="';
            html += p_style;
            html += '"><h6>';
            html += obj[i].owner;
            html += '</h6></a>';
            html += '</td>';
            html += '<td>';
            html += '<h6 style="color:#868e96">'+nbs[i - 1]+'</h6>';
            html += '</td>';
            html += '<td>';
            html += '<h6 style="color:#868e96">';
            html += obj[i].last_message_date;
            html += '</h6>';
            html += '</td>';
            html+= '</tr>';
        
            if(i == request_count)
            {
                html += '</tbody></table>';
                break;
            }
            if(i==last_topic_id)
            {
                html += '</tbody></table>';
            }
        }
    
        div.innerHTML=html;
    }
    
    document.querySelector('#prev').addEventListener('click', function() {
        
        if(page > 1)
        {
            page--;
            document.location.href="forum.php?page="+page;
        }
        
    });
    document.querySelector('#next').addEventListener('click', function() {
        
        if(page < max_page)
        {
            page++;
            document.location.href="forum.php?page="+page;
        }
    });
    
    var prev = document.querySelector('#prev');
    var next = document.querySelector('#next');
    var btn_div1 = document.querySelector('#btn-div1');
    var btn_div2 = document.querySelector('#btn-div2');
    
    if(page==1) { btn_div1.removeChild(prev); }
    else { btn_div1.appendChild(prev); }
    if(page==max_page){btn_div2.removeChild(next); }
    else { btn_div2.appendChild(next); }
    
    document.querySelector('#submit-btn').addEventListener('click', function() {
       
        var title = document.querySelector('#title').value;
        var message = document.querySelector('#message').value;
        var connected = '<?php if(isset($_SESSION['connected'])) { echo $_SESSION['connected']; } else { echo "false"; } ?>';
        
        if(title!="" && message!="" && message.length <=65000 && connected=="true")
        {
            document.querySelector('#topic-form').submit();
        }
        else
        {
            if(connected=="false")
            {
                document.location.href="connexion.php";
            }
        }
        
    });
    
    function input_style(balise, textarea)
    {
        var insert = '['+balise+']'+'[/'+balise+']';
        document.querySelector(textarea).value += insert;
    }
    
</script>

<?php
    
$footer->display();

?>