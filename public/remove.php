<?php

if($_SERVER["REQUEST_METHOD"]==="DELETE"){
    header('Content-Type: application/json; charset=utf-8');
    $videoToBeDeleted=$_REQUEST['video_delete'];
   include("../db-baglanti/db_baglantim.php");
   $video=$baglanti->prepare("SELECT video_path FROM videos WHERE id=$videoToBeDeleted");
   $video->execute();
   $random = $video->fetchColumn();

   $deleteProcess = $baglanti->query("DELETE  FROM videos WHERE id = $videoToBeDeleted ");
   $deleteProcess-> execute();
   $videoToBeDeleted = unlink("uploads/".$random);



   echo json_encode(['success' => true]);





}




