<?php
session_start();
require_once("database.inc.php");

if(isset($_POST['code']))
{
    $database = new database();
    
    $pre_user = $database->select("SELECT * FROM preinscription WHERE pseudo={$_SESSION['preinscription-pseudo']}");
    $code_enter = $_POST['code'];
    
    if($code_enter == $pre_user[1]->code)
    {
        $date = date("m.d.y");
        $hash = password_hash($pre_user[1]->password, PASSWORD_DEFAULT);
        $password = crypt($pre_user[1]->password, $hash);
        $database->insert("INSERT INTO user VALUES('{$pre_user[1]->pseudo}', '{$pre_user[1]->mail}', '{$password}', '{$date}', 'user', 'default.png', '', '')");
        
        $_SESSION['connected'] = "true";
        $_SESSION['pseudo'] = $pre_user[1]->pseudo;
        unset($_SESSION['preinscription-pseudo']);
        $database->delete("DELETE FROM preinscription WHERE pseudo='{$pre_user[1]->pseudo}'");
        echo"<script> document.location.href=\"account.php\"</script>";
    }
    else
    {
        echo "<form method=\"post\" action=\"inscription.php\" id=\"preinscription-form\">
                <input type=\"text\" id=\"preinscription-statut\" name=\"preinscription-statut\" hidden/>
            </form>";
        
        echo "<script>
                    document.querySelector('#preinscription-statut').value='failure-again';
                    document.querySelector('#preinscription-form').submit();
                 </script>";
    }
}
else
{
    header("Location:index.php");
}

?>
