<?php session_start();
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
<body>
<div class="main-content">
<?php
require_once("config.inc.php");
$aPath = ASSETSPATH;
include($aPath . "header.php");
?>
<div class="image">
    <?php
$auth = new Authentication();
$follow = new Follow();
$imgP = new ImageProcessing();
foreach($auth->getUser() as $user):
    if($username == $user['Username']):
        foreach($follow->getFollow() as $f):
            if($user['ID'] == $f['UserID']):
                foreach ($imgP->getImg() as $img):
                    if($f['Follows'] == $img['UserID'] && $f['Status'] == 1):
                        ?>
                    <img src="./thumbnails/<?= $img['image']?>.jpg">
                    <?php
                        endif;
                    endforeach;
                endif;
            endforeach;
    endif;
    endforeach;
?>
    <div class="image"
</div>
</body>
</html><?php
