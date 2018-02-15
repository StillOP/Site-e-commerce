<?php
session_start();
require_once("database.inc.php");

if(isset($_POST['comment-message']))
{
    $database = new database();
    $date = date("Y-m-d H:i:s");
    $message = htmlspecialchars($_POST['comment-message']);
    
    $database->insert("INSERT INTO comment VALUES('\N', '{$_POST['type']}', '{$_POST['article_id']}', '{$_SESSION['pseudo']}','{$message}', '{$date}')");
    
    echo "<script>document.location=\"article.php?type={$_POST['type']}&article_id={$_POST['article_id']}\"; </script>";
}
else
{
    echo "<script>document.location=\"index.php\";</script>";
}

?>