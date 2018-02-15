<?php 
session_start();

if(isset($_POST['logout']))
{
    session_unset();
    session_destroy();
    echo"<script> document.location.href=\"index.php\"</script>";
}
?>

<?php
require_once("header.inc.php");
require_once("footer.inc.php");

$header = new header();
$footer = new footer();
$header->display("Acceuil", $_SESSION);

?>
<div style="position:fixed;background-image:url(back.png);filter:blur(25px);z-index:-2;height:100%;width:100%;"></div>
<div style="padding:50px;margin-top:50px;color:white;">
    <h1 style="background-color:black;">Welcome to <span style="color:#dc3545">Kreativ.com!</span></h1>
            <br/>
    <h1 style="background-color:black;">Share your creations (music, books, goodies) with the community.</h1>
    <h1 style="background-color:black;">This one can support you by buying your creations at a fair price.</h1>
    <h1 style="background-color:black;">You are a musicians, a writer, a painter, a sculteurs ...</h1>
    <h1 style="background-color:black;">Whether you are a beginner or a professional, this community is for you.</h1>
    <h1 style="background-color:black;">Enter the Kreativ world <a href="connexion.php" style="color:#51acc7;">now!</a></h1>
    <br/><br/><br/><br/><br/>
</div>

<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="contactlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="contactlabel">Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form method="post" action="contact.php" id="contact-form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="contact-mail" id="contact-mail" placeholder="Email" pattern="(^[a-z0-9._-]+)@([a-z0-9._-])+(\.)([a-z]{2,4})" />
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="contact-content" id="contact-content" rows="10"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="contact-submit" onclick="send()">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
    
    function send()
    {
        var mail = document.querySelector('#contact-mail').value;
        var content = document.querySelector('#contact-content').value;
        
        if(/(^[a-z0-9._-]+)@([a-z0-9._-])+(\.)([a-z]{2,4})/.test(mail) && mail != "" && mail.length < 255 && content != "" && content.length <= 65000)
        {
            document.querySelector('#contact-form').submit();
        }
    }
    
</script>

<?php

$footer->display();

?>
