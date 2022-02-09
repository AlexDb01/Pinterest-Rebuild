<?php
session_start();
require_once('config.inc.php');
if (!isset($_SESSION['loggedIn'])) {
    header("Location: index.php");
}
$username = $_SESSION['username'];
?>
    <!doctype html>
    <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        refreshListAcc();

        $(".pfpUpload").submit(function (event) {
            event.preventDefault();
            var fd = new FormData();
            var images = $("input[name=pfp_image_upload]")[0].files;
            var users = $("input[name=userIDpfp]").val();
            fd.append('pfpImage', images[0]);
            fd.append('perform', 'savePfp');
            fd.append('user', users);
            var request = $.ajax({
                url: "ajax.php",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false
            });
            request.done(function (answer) {
                console.log(answer);
                refreshListAcc();
            })

        });
    })

    function refreshListAcc() {
        var url = $(location).attr('href');
        var urlParam = url.indexOf("=");
        var urlSubStr = url.substr(urlParam + 1, url.length);
        var request = $.ajax({
            url: "ajax.php",
            dataType: "html",
            method: "POST",
            data: {
                user: urlSubStr,
                perform: "displayPfp"
            }
        });

        request.done(function (answer) {
            console.log(answer);
            $(".pfpImgAcc").html(answer);
        })
    }
</script>
<body>
<?php
require_once("config.inc.php");
$aPath = ASSETSPATH;
include($aPath . "header.php");
?>
<div class="main-content">
    <div class="accInfo">
<h2>Profilbild ändern</h2>
<form class="pfpUpload" action="" method="POST" enctype="multipart/form-data">
    <label class="custom-file-upload">
    <input class="file" type="file" name="pfp_image_upload">
        Bild auswählen
    </label>
    <input class="imguploadbtn" type="submit" value="Bild Hochladen" name="pfpUpload">
    <?php
    $auth = new Authentication();
    foreach ($auth->getUser() as $user):
        if ($user['Username'] == $username):
            ?>
            <input type="hidden" name="userIDpfp" value="<?= $user['ID'] ?>">
        <?php
        endif;
    endforeach;
    ?>
</form>
<div class="pfpImgAcc"></div>
    </div>
<div class="accountImgs">
    <h1 class="deinebilder">Deine Bilder</h1>
    <?php
    $imgP = new ImageProcessing();
    foreach ($auth->getUser() as $user):
    if ($user['Username'] == $username):
    foreach ($imgP->getImg() as $img):
    if ($user['ID'] == $img['UserID']):
    ?>
    <a href="image.php?img=<?= $img['image'] ?>">
        <img src="./thumbnails/<?= $img['image'] ?>.jpg">
        <?php
        endif;
        endforeach;
        endif;
        endforeach;
        ?>
</div>
</div>
</body>
    </html><?php
