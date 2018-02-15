<?php
require_once("download.php");
require_once("database.inc.php");

if(isset($_POST['type']))
{
    $name = str_replace('_', ' ', $_POST['title']);
    
    if($_POST['type'] == "album") 
    { 
        download_album($name, $_POST['id']); 
    }
    if($_POST['type'] == "book")
    {
        download_book($name, $_POST['id']);
    }
    
     echo "<div style=\"margin:100px;\"><h1 style=\"color:#28a745;\">Thank for your purchase!</h1>
        <a href=\"index.php\">Back to index!</a></div>";
    echo"<script> document.location.href=\"index.php\"</script>";
}

?>