<?php
session_start();
require_once("database.inc.php");

if(isset($_POST['message']) && isset($_POST['title']))
{
    $database = new database();
    $date = date("Y-m-d H:i:s");
    $message = htmlspecialchars($_POST['message']);
    $title = htmlspecialchars($_POST['title']);
    $topic_request = "INSERT INTO topic VALUES('\N', '{$title}', '{$_SESSION['pseudo']}', '{$date}', '{$date}', 'normal')";
    $topic_id = $database->insert($topic_request);
    $response_request = "INSERT INTO response VALUES('\N', '{$topic_id}', '{$_SESSION['pseudo']}', '{$message}','{$date}')";
    $database->insert($response_request);
    echo "<script> document.location.href=\"forum.php?page={$_GET['page']}\";</script>";
}

?>