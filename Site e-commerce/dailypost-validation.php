<?php
session_start();
require_once("usermanager.inc.php");

$database = new database();


if(isset($_POST['new-post-title']) && isset($_POST['new-post-content']))
{
    $database->insert("INSERT INTO post VALUES('\N', '{$_POST['id']}', '{$_POST['new-post-title']}', '{$_POST['new-post-content']}')");
    header("Location:article.php?type=dailypost&article_id={$_POST['id']}");
    
    $subscribers = $database->select("SELECT * FROM subscriber WHERE dailypost_id='{$_POST['id']}'");
    $dailypost_title = $database->select("SELECT title FROM dailypost WHERE dailypost_id='{$_POST['id']}'");
    $dailypost_title = $dailypost_title[1]->title;
    $date = date("Y-m-d H:i:s");
    $message_title = $dailypost_title.' has a new chapter!';
    
    for($i = 1; $i <= $subscribers[0]; $i++)
    {
        $database->insert("INSERT INTO message VALUES('\N', 'Subscribing', '{$subscribers[$i]->pseudo}', '{$message_title}', '{$message_title}', 'unread', 'normal', '{$date}')");
    }
}
?>