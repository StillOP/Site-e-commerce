<?php
session_start();
require_once("database.inc.php");

if(isset($_POST['reply-content']))
{
    $database = new database();
    $date = date("Y-m-d H:i:s");
    $content = htmlspecialchars($_POST['reply-content']);
    $title = 'Re:'.$_POST['title'];
    
    $database->insert("INSERT INTO message VALUES('\N', '{$_SESSION['pseudo']}', '{$_POST['receiver']}', '{$title}', '{$content}', 'unread', 'normal', '{$date}')");
    
    echo "<script>document.location=\"mailbox.php\";</script>";
}
else
{
    echo "<script>document.location=\"index.php\";</script>";
}
