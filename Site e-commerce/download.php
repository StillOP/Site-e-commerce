<?php

function download_album($name, $id)
{
    $path = 'albums/'.$id.'.zip';
    $size = filesize($path);
    
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: $size");
    header("Content-Disposition: attachment; filename=\"".$name.'.zip'. "\"");
    header("Expires: 0");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    readfile("$path");
}

function download_book($name, $id)
{
    $database = new database();
    $file = $database->select("SELECT file FROM book WHERE book_id='{$id}'");
    $path = 'books/'.$file[1]->file;
    $size = filesize($path);
    
    header("Content-Type: application/pdf");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: $size");
    header("Content-Disposition: attachment; filename=\"".$name.'.pdf'. "\"");
    header("Expires: 0");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    readfile("$path");
}


?>