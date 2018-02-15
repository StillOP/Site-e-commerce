<?php
session_start();
require_once("database.inc.php");

if(isset($_POST['new-message-title']) && isset($_POST['new-message-content']))
{
    $database = new database();
    $date = date("Y-m-d H:i:s");
    $database->insert("INSERT INTO message VALUES('\N', '{$_SESSION['pseudo']}', '{$_POST['receiver']}', '{$_POST['new-message-title']}','{$_POST['new-message-content']}', 'unread', 'normal', '{$date}')");
    
    echo "<script>document.location=\"profil.php?pseudo={$_POST['receiver']}\";</script>";
}
else
{
    header("Location:index.php");
}

?>