<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Main</title>

    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
          rel="stylesheet"/>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link rel="stylesheet" href="navbarm.css">

    <style type="text/css">
        body {
            margin: 0;
            background: #f7f7f7;
        }


        .filepond--drop-label {
            background-color: #edf2f8;
            border-radius: 8px;
        }

        .container {
            margin-top: 24px;
            height: calc(100vh - 104px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            position: relative;
        }

        .container .box {
            background-color: #fff;
            max-width: 570px;
            border-radius: 8px;
            max-height: 620px;
            width: 100%;
            padding: 16px 0;
            flex: 1;
        }

        .container .box .title {
            color: #071449;
            font-size: 28px;
            font-weight: bold;
            border-bottom: 1px solid #f3f3f3;
            font-family: 'OpenSans', sans-serif;
            text-align: center;
            padding-bottom: 12px;
        }

        .container .box .upload-box {
            padding: 16px;
        }

        .alert-box {
            max-width: 540px;
            width: 100%;
            padding: 16px;
            border-radius: 8px;
            color: white;
            position: absolute;
            top: 15px;
            text-align: center;
        }

        .alert-box.error {
            background-color: #FC4F4F;
        }

        .alert-box.success {
            background-color: #8B9A46;
        }


    </style>
</head>
<body>
<?php
include("navbar.php");

$appUrl = $_ENV['APP_URL'];
?>
<div class="container">


    <div class="box">
        <div class="title">Dosya Yükle</div>

        <div class="upload-box">
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input
                        class="filepond"
                        type="file"
                        name="dosya"
                        multiple
                        accept="video/*">

            </form>
        </div>


    </div>
</div>

<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script
        src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="navbar.js"></script>
<script>
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview
    );

    fetch("<?php echo $appUrl ?>/videos.php")
        .then(response => response.json())
        .then(data => {
            FilePond.create(document.querySelector(".filepond"), {
                files: data,
                allowMultiple: true,
                allowReorder: true,
                labelFileLoading: "Yükleniyor...",
                labelTapToCancel: "İptal etmek için tıklayın",
                labelTapToRetry: "Tekrar denemek için tıklayın",
                labelTapToUndo: "Geri almak için tıklayın",
                labelButtonRemoveItem: "Kaldır",
                labelFileWaitingForSize: "Boyutlandırılıyor...",
                labelIdle: 'Dosyalarınızı sürükleyip bırakabilirsiniz ya da <span class="filepond--label-action"> Dosyalarda arayın </span>',

                server: {
                    load: (uniqueFileId, load, error, progress, abort, headers) => {
                        console.log('attempting to load', uniqueFileId);

                        let controller = new AbortController();
                        let signal = controller.signal;

                        fetch(`load.php?video_id=${uniqueFileId}`, {
                            method: "GET",
                            signal,
                        })
                            .then(res => {
                                window.c = res
                                console.log(res)
                                return res.blob()
                            })
                            .then(blob => {
                                console.log(blob)

                                load(blob);
                            })
                            .catch(err => {
                                console.log(err)
                                error(err.message);
                            })
                        return {
                            abort: () => {

                                controller.abort();
                                abort();
                            }
                        };
                    },
                }
            });

            FilePond.setOptions({
                server: {
                    url: '<?php echo $appUrl ?>',
                    process: {
                        url: '/upload.php',
                        method: 'POST',
                        withCredentials: false,
                        headers: {},
                        timeout: 7000,
                        onload: null,
                        onerror: null,
                        ondata: null,
                    },
                    remove:(uniqueFileId,load,error)=>{
                        const formData = new FormData();
                        formData.append("video_id",uniqueFileId);

                        console.log(uniqueFileId);

                        fetch(`remove.php?video_delete=${uniqueFileId}`,{
                            method:"DELETE",
                            body:formData,
                        })
                        .then(res=> res.json())
                        .then(json=>{
                            console.log(json);
                            if(json.status == "success"){
                                load();
                            }else{

                                error(err.msg);
                            }
                        })
                        .catch(err =>{
                            console.log(err)

                            error(err.message);
                        })
                    },
                },
            });
        });
</script>


</body>
</html>
