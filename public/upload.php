<?php

session_start();

if (isset($_FILES["dosya"])) {
    $fileName = $_FILES['dosya']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, ["jpg", "png", "jpeg", "mp4"])) {
        $response = [
            'success' => false,
            'message' => 'Sadece jpg, png, jpeg ve mp4 uzantılı dosyalar yüklenebilir.'
        ];

        echo json_encode($response);

        return;
    }

    include("../db-baglanti/db_baglantim.php");


    $fileExists = true;
    do {
        $str = rand();
        $fileName = md5($str).'.'.$fileExtension;

        $checkFileExistsQuery = $baglanti->prepare('SELECT count(*) FROM videos WHERE user_id = :userId AND video_path = :videoPath');
        $checkFileExistsQuery->execute([
            'userId' => $_SESSION['user']['id'],
            'videoPath' => $fileName
        ]);
        $count = $checkFileExistsQuery->fetchColumn();

        if (!$count) {
            $fileExists = false;
        }
    } while($fileExists);

    $uploadVideoQuery = $baglanti->prepare("INSERT INTO videos (video_path, user_id) VALUES (:videoPath, :userId)");
    $uploadVideoQuery->execute(['videoPath' => $fileName, 'userId' => $_SESSION['user']['id']]);

    $targetPath = __DIR__ . DIRECTORY_SEPARATOR . 'uploads/' . DIRECTORY_SEPARATOR . $fileName;
    move_uploaded_file($_FILES['dosya']['tmp_name'], $targetPath);

    $response = [
        'success' => true,
        'message' => 'Dosyanız başarıyla yüklenmiştir.',
    ];

    echo json_encode($response);

    return;
}


