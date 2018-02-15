<?php
session_start();
require_once("database.inc.php");


if(isset($_POST['pseudo']) && isset($_POST['dailypost_id']))
{
    $database = new database();
    if(isset($_POST['subscribe']))
    {
        $database->insert("INSERT INTO subscriber VALUES('\N', '{$_POST['pseudo']}', '{$_POST['dailypost_id']}')");
    }
    else if(isset($_POST['unsubscribe']))
    {
        $database->delete("DELETE FROM subscriber WHERE pseudo='{$_POST['pseudo']}'");
    }
    header("Location:article.php?type=dailypost&article_id={$_POST['dailypost_id']}");
}
else
{
    header("Location:index.php");
}
?>