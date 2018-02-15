<?php

session_start();
require_once("usermanager.inc.php");
require_once("form.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");

if(!isset($_SESSION['connected'])) { echo"<script> document.location.href=\"inscription.php\"</script>"; }

$header = new header();
$form = new form();
$footer = new footer();

$header->display("Register", $_SESSION);

?>

<style>
    .nav-link{
        color:#dc3545;
    }
</style>
<ul class="nav nav-tabs" role="tablist" id="tab" style="margin-top:12px;">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#album" role="tab" aria-controls="album" aria-selected="true" id="album-tab">Album</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#book" role="tab" aria-controls="book" aria-selected="false" id="book-tab">Book</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#dailypost" role="tab" aria-controls="dailypost" aria-selected="false" id="dailypost-tab">Daily post</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#goodies" role="tab" href="#goodies" aria-controls="goodies" aria-selected="false" id="goodies-tab">Goodies &amp; others </a>
  </li>
</ul>

<?php

function display_error($message, $id='#album_error')
{
    echo "<script> 
                function errorset(message)
                {
                    return '<div class=\"alert alert-danger alert-dismissible fade show mt-2\" role=\"alert\">'+
                                        '<strong>'+message+
                                        '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">'+
                                            '<span aria-hidden=\"true\">&times;</span>'+
                                        '</button>'+
                                '</div>';
                }
                
                var errorDiv = document.createElement('div');
                errorDiv.id = 'error';
                document.querySelector('{$id}').appendChild(errorDiv);
                errorDiv.innerHTML += errorset(\"{$message}\");
            </script>";
}

?>

<?php 
//gestion de la limte de taille
$form->divheader("tab-content");
require_once("album.register.inc.php");
require_once("book.register.inc.php");
require_once("dailypost.register.inc.php");
require_once("goodies.register.inc.php");
$form->divfooter();
$footer->display();

$tab;
if(isset($_SESSION['last_registered'])) { $tab = $_SESSION['last_registered']; }
else { $tab= '#album'; }

?>
<script>
    var last_registered = "<?php echo $tab; ?>";
    $('#tab a[href="'+last_registered+'"]').tab('show');
</script>