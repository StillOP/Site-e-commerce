<?php
session_start();
require_once("database.inc.php");


if(isset($_POST['contact-mail']) && isset($_POST['contact-content']))
{
    $database = new database();
    
    $mail = $_POST['contact-mail'];
    $content = $_POST['contact-content'];
    $date = date("Y-m-d H:i:s");
    
    $database->insert("INSERT INTO contact VALUES('\N','{$mail}', '{$content}', '{$date}')");
    
    header("Location:index.php");
}
else
{
    header("Location:index.php");
}
?>