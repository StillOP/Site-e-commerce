<?php
session_start();
require_once("database.inc.php");

if(isset($_POST['message']) && isset($_GET['id']) && isset($_GET['topic_page']))
{
    $database = new database();
    
    $id = $database->format($_GET['id']);
    $validation = $database->select("SELECT * topic WHERE topic_id={$id}");
    if($validation[0] == 0) { echo "<script> document.location.href=\"forum.php?page=1\";</script>"; }
    if($validation[1]->title != $_GET['topic_page']) { echo "<script> document.location.href=\"forum.php?page=1\";</script>"; }
    
    
    $date = date("Y-m-d H:i:s");
    $message = $database->format($_POST['message']);
    $post_request = "INSERT INTO response VALUES('\N', '{$_GET['id']}', '{$_SESSION['pseudo']}', '{$message}', '{$date}')";
    $topic_request = "UPDATE topic SET last_message_date = '{$date}' WHERE topic_id = '{$_GET['id']}'";
    
    $database->insert($post_request);
    $database->update($topic_request);
    echo "<script> document.location.href=\"topic.php?id={$_GET['id']}&title={$_GET['title']}&topic_page={$_GET['topic_page']}\";</script>";  
}
else if(!isset($_SESSION['connected']) || !isset($_SESSION['pseudo']))
{
    echo "<script> document.location.href=\"inscription.php\"</script>";
}
else
{
    header("Location:index.php");
}

?>