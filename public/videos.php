<?php
session_start();

include("../db-baglanti/db_baglantim.php");

$videosQuery = $baglanti->prepare("SELECT id FROM videos WHERE user_id=:user_id");
$videosQuery->execute(["user_id" => $_SESSION['user']['id']]);

$videos = $videosQuery->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json; charset=utf-8');

if (empty($videos)) {
    echo json_encode($videos);

    return;
}

$videoPaths = array_map(function ($videoPath) {
    return [
        'source' => $videoPath,
        'options' => [
            'type' => 'local'
        ]
    ];
}, array_column($videos, 'id'));

echo json_encode($videoPaths);



















