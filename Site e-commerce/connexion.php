<?php

session_start();
require_once("usermanager.inc.php");
require_once("form.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");
require_once("mail.param.inc.php");

if(isset($_SESSION['connected']) && $_SESSION['connected'] == "true") { echo"<script> document.location.href=\"index.php\"</script>"; }

$header = new header();
$form = new form();
$footer = new footer();

$database = new database();

$header->display("Connexion", $_SESSION);
    $form->header($_SERVER['PHP_SELF'], "connexionform");
         $form->divheader("form-group");
            $form->input("pseudo", "Pseudo or mail", "text", "pseudo", "Pseudo or mail");
        $form->divfooter();
         $form->divheader("form-group");
            $form->input("password", "Password", "password", "password", "Password");
        $form->divfooter();
        $form->button("btn btn-primary", "Submit", "submit");
    $form->footer();

echo "<br/><p><a href=\"inscription.php\" class=\"text-info ml-3\">No account? Register now.</a></p>";


if(isset($_POST['pseudo']) && isset($_POST['password']))
{
    $pseudo = $database->format($_POST['pseudo']);
    $result = $database->select("SELECT pseudo, mail, password FROM user WHERE pseudo={$pseudo} OR mail={$pseudo}");
    
    if($result[0] !=0)
    {
        if(password_verify($_POST['password'], $result[1]->password))
        {
            $_SESSION['connected'] = "true";
            $_SESSION['pseudo'] = $result[1]->pseudo;
            echo"<script> document.location.href=\"account.php\"</script>";
        }
        else
        {
            echo "<script>
                document.querySelector('body').innerHTML += '<div class=\"alert alert-danger alert-dismissible fade show mt-2 mb-2\" role=\"alert\">'+
                            '<strong> Invalid login or password'+
                            '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">'+
                                '<span aria-hidden=\"true\">&times;</span>'+
                            '</button>'+
                        '</div>';
            </script>";
        }
    }
    else 
    { 
        echo "<script>
                document.querySelector('body').innerHTML += '<div class=\"alert alert-danger alert-dismissible fade show mt-2 mb-2\" role=\"alert\">'+
                            '<strong> Invalid login or password'+
                            '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">'+
                                '<span aria-hidden=\"true\">&times;</span>'+
                            '</button>'+
                        '</div>';
            </script>";
    }
}

$footer->display();
?>