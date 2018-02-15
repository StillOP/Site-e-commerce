<?php
session_start();
require_once("database.inc.php");
require_once("mail.param.inc.php");

if(isset($_POST['mail']) && isset($_POST['password']) && isset($_POST['confirm']) && isset($_POST['pseudo']) && isset($_POST['check']))
{
    $database = new database();
    
    $mail = $database->format($_POST['mail']);
    $password = $database->format($_POST['password']);
    $confirm = $database->format($_POST['confirm']);
    $pseudo= $database->format($_POST['pseudo']);
    $check = $database->format($_POST['check']);
    
    $user = $database->select("SELECT pseudo FROM user WHERE pseudo={$pseudo}");
    
    echo "<form method=\"post\" action=\"inscription.php\" id=\"preinscription-form\">
            <input type=\"text\" id=\"preinscription-statut\" name=\"preinscription-statut\" hidden/>
        </form>";
    
    if($mail!="" && $password!="" && $confirm!="" && $password==$confirm && $pseudo!="" && $check!=false && strlen($password)>=8 && strlen($password)<=20 && strlen($pseudo)>=8 && strlen($pseudo)<=20 && $user[0] == 0 && preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/", trim($_POST['password'])) && filter_var(trim($_POST['mail']), FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-Z0-9._'-]*$/", trim($_POST['pseudo'])))
    {
        $pre_user = $database->select("SELECT pseudo FROM preinscription WHERE pseudo={$pseudo}");
        if($pre_user[0] == 0)
        {
            $_SESSION['preinscription-pseudo'] = $pseudo;
            $code= mt_rand(1000, 9999);
            
            $database->insert("INSERT INTO preinscription VALUES({$pseudo}, {$mail}, {$password}, {$code})");
            mail("newuser@localhost.com", OBJECT, MESSAGE.' '.$code, HEADER);
            
            echo "<script>
                    document.querySelector('#preinscription-statut').value=\"success\";
                    document.querySelector('#preinscription-form').submit();
                 </script>";
        }
        else
        {
            $database->delete("DELETE FROM preinscription WHERE pseudo='{$pre_user[1]->pseudo}'");
            echo "<script> document.location.href=\"inscription.php\"</script>";
        }
    }
    else
    {
        echo "<script>
                    document.querySelector('#preinscription-statut').value='failure';
                    document.querySelector('#preinscription-form').submit();
                 </script>";
    }
}
else
{
    header("Location:index.php");
}

?>
