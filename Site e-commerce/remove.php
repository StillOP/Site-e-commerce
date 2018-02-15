<?php
session_start();
require_once("database.inc.php");

function delete_album($id)
{
    $dir = 'albums/'.$id.'-read';
    if (is_dir($dir)) 
    {
        $objects = scandir($dir);
        foreach ($objects as $object) 
        {
            if ($object != "." && $object != "..") 
            {
                if (filetype($dir."/".$object) == "dir") { rrmdir($dir."/".$object); } 
                else { unlink($dir."/".$object); }
            }
        }
        reset($objects);
        rmdir($dir);
    }
    
    $database = new database();
    
    $cover_path = $database->select("SELECT cover FROM album WHERE album_id='{$id}'");
    
    $archive = 'albums/'.$id.'.zip';
    $cover = 'covers/'.$cover_path[1]->cover;
    unlink($archive);
    unlink($cover);
    
    $database->delete("DELETE FROM track WHERE album_id='{$id}'");
    $database->delete("DELETE FROM album WHERE album_id='{$id}'");
    $comments = $database->select("SELECT * FROM comment WHERE article_type='album' AND article_id='{$id}'");
    if($comments[0] > 0)
    {
        $database->delete("DELETE FROM comment WHERE article_type='album' AND article_id='{$id}'");
    }
}

function delete_book($id)
{
    $database = new database();
    
    $file_path =  $database->select("SELECT file FROM book WHERE book_id='{$id}'");
    $sample_path = $database->select("SELECT sample FROM book WHERE book_id='{$id}'");
    $cover_path = $database->select("SELECT cover FROM book WHERE book_id='{$id}'");
    
    $file = 'books/'.$file_path[1]->file;
    $sample = 'samples/'.$sample_path[1]->sample;
    $cover = 'covers/'.$cover_path[1]->cover;
    
    unlink($file);
    unlink($sample);
    unlink($cover);
    
    $database->delete("DELETE FROM book WHERE book_id='{$id}'");
    $comments = $database->select("SELECT * FROM comment WHERE article_type='book' AND article_id='{$id}'");
    if($comments[0] > 0)
    {
        $database->delete("DELETE FROM comment WHERE article_type='book' AND article_id='{$id}'");
    }
}

function delete_dailypost($id)
{
    $database = new database();
    
    $subscribers = $database->select("SELECT * FROM subscriber WHERE dailypost_id='{$id}'");
    if($subscribers[0] > 0)
    {
        $database->delete("DELETE FROM subscriber WHERE dailypost_id='{$id}'");
    }
    
    $database->delete("DELETE FROM post WHERE dailypost_id='{$id}'");
    $database->delete("DELETE FROM dailypost WHERE dailypost_id='{$id}'");
    $comments = $database->select("SELECT * FROM comment WHERE article_type='dailypost' AND article_id='{$id}'");
    if($comments[0] > 0)
    {
        $database->delete("DELETE FROM comment WHERE article_type='dailypost' AND article_id='{$id}'");
    }
}

function delete_goodies($id)
{
    $database = new database();
    
    $images = $database->select("SELECT images FROM goodies WHERE goodies_id='{$id}'");
    $images_tab = explode(",", $images[1]->images);
    
    for($i = 0; $i < sizeof($images_tab); $i++)
    {
        $path = 'covers/'.$images_tab[$i];
        unlink($path);
    }
    
    $database->delete("DELETE FROM goodies WHERE goodies_id='{$id}'");
    $comments = $database->select("SELECT * FROM comment WHERE article_type='goodies' AND article_id='{$id}'");
    if($comments[0] > 0)
    {
        $database->delete("DELETE FROM comment WHERE article_type='goodies' AND article_id='{$id}'");
    }
}

if(isset($_POST['type']) && isset($_POST['id']))
{
    $database = new database();
    
    if($_POST['type'] == "received_message")
    {
        $database->update("UPDATE message SET receiver_statut='deleted' WHERE message_id='{$_POST['id']}'");
        echo "<script>document.location=\"mailbox.php\";</script>";
    }
    else if($_POST['type'] == "sent_message")
    {
        $database->update("UPDATE message SET sender_statut='deleted' WHERE message_id='{$_POST['id']}'");
        echo "<script>document.location=\"mailbox.php\";</script>";
    }
    else if($_POST['type'] == "album")
    {
        delete_album($_POST['id']);
        echo"<script> document.location.href=\"account.php\"</script>";
    }
    else if($_POST['type'] == "book")
    {
        delete_book($_POST['id']);
        echo"<script> document.location.href=\"account.php\"</script>";
    }
    else if($_POST['type'] == "goodies")
    {
        delete_goodies($_POST['id']);
        echo"<script> document.location.href=\"account.php\"</script>";
    }
    else if($_POST['type'] == "dailypost")
    {
        delete_dailypost($_POST['id']);
        echo"<script> document.location.href=\"account.php\"</script>";
    }
    else if($_POST['type'] == "response")
    {
        $topic_id = $database->select("SELECT topic_id FROM response WHERE response_id='{$_POST['id']}'");
        $nb = $database->select("SELECT response_id FROM response WHERE topic_id='{$topic_id[1]->topic_id}'");
        if($nb[0] == 1)
        {
            $database->delete("DELETE FROM response WHERE topic_id='{$topic_id[1]->topic_id}'");
            $database->delete("DELETE FROM topic WHERE topic_id='{$topic_id[1]->topic_id}'");
        }
        else
        {
            $database->delete("DELETE FROM response WHERE response_id='{$_POST['id']}'");
        }
    }
    else if($_POST['type'] == "topic")
    {
        $database->delete("DELETE FROM response WHERE topic_id='{$_POST['id']}'");
        $database->delete("DELETE FROM topic WHERE topic_id='{$_POST['id']}'");
    }
    else if($_POST['type'] == "user")
    {
        $album = $database->select("SELECT album_id FROM album WHERE owner='{$_POST['id']}'");
        for($i = 1; $i <= $album[0]; $i++)
        {
            delete_album($album[$i]->album_id);
        }
        $book = $database->select("SELECT book_id FROM book WHERE owner='{$_POST['id']}'");
        for($i = 1; $i <= $book[0]; $i++)
        {
            delete_book($book[$i]->book_id);
        }
        $dailypost = $database->select("SELECT dailypost_id FROM dailypost WHERE owner='{$_POST['id']}'");
        for($i = 1; $i <= $book[0]; $i++)
        {
            delete_dailypost($dailypost[$i]->dailypost_id);
        }
        $goodies = $database->select("SELECT goodies_id FROM goodies WHERE owner='{$_POST['id']}'");
        for($i = 1; $i <= $goodies[0]; $i++)
        {
            delete_goodies($goodies[$i]->goodies_id);
        }
        $response = $database->select("SELECT response_id FROM response WHERE owner='{$_POST['id']}'");
        if($response[0] > 0)
        {
            $database->delete("DELETE FROM response WHERE owner='{$_POST['id']}'");
        }
        $topic = $database->select("SELECT topic_id FROM topic WHERE owner='{$_POST['id']}'");
        if($topic[0] > 0)
        {
            $database->delete("DELETE FROM topic WHERE owner='{$_POST['id']}'");
        }
        $comments = $database->select("SELECT * FROM comment WHERE owner='{$_POST['id']}'");
        if($comments[0] > 0)
        {
            $database->delete("DELETE FROM comment WHERE owner='{$_POST['id']}'");
        }
        $subscribing = $database->select("SELECT * FROM subscriber WHERE pseudo='{$_POST['id']}'");
        if($subscribing[0] > 0)
        {
            $database->delete("DELETE FROM subscriber WHERE pseudo='{$_POST['id']}'");
        }
        $sent = $database->select("SELECT message_id FROM message WHERE sender='{$_POST['id']}'");
        if($sent[0] > 0)
        {
            for($i = 1; $i <= $sent[0]; $i++)
            {
                $database->update("UPDATE message SET sender_statut='deleted' WHERE message_id='{$sent[$i]->message_id}'");
            }
        }
        $database->delete("DELETE FROM user WHERE pseudo='{$_POST['id']}'");
        session_unset();
        session_destroy();
        echo"<script> document.location.href=\"index.php\"</script>";
    }
    
}
else
{
    echo "<script>document.location=\"index.php\";</script>";
}
