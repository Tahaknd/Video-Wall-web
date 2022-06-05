<?php

session_start();

// Comment if you don't want to allow posts from other domains
header('Access-Control-Allow-Origin: *');

// Allow the following methods to access this file
header('Access-Control-Allow-Methods: OPTIONS, GET, DELETE, POST, HEAD, PATCH');

// Allow the following headers in preflight
header('Access-Control-Allow-Headers: content-type, upload-length, upload-offset, upload-name');

// Allow the following headers in response
header('Access-Control-Expose-Headers: upload-offset');

if ($videoId = $_GET['video_id']) {
    include('../db-baglanti/db_baglantim.php');

    $findVideoQuery = $baglanti->prepare('SELECT video_path FROM videos WHERE id = :id AND user_id = :userId');
    $findVideoQuery->execute(['id' => $videoId, 'userId' => $_SESSION['user']['id']]);
    $videoPath = $findVideoQuery->fetchColumn();

    $fileBlob = file_get_contents('uploads/'.$videoPath);
    $imagePath='uploads/'.$videoPath;
    $fileContextType = mime_content_type($imagePath);
    $fileSize = filesize($imagePath);


    http_response_code(200);

    header("Content-Type: $fileContextType");


    echo $fileBlob;

}

