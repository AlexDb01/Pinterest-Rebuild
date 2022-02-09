<?php
require_once("config.inc.php");
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
    $(document).ready(function(){
        refreshListComments();

        $(".commentForm").submit(function (event){
            var url = $(location).attr('href');
            var urlParam = url.indexOf("=");
            var urlSubStr = url.substr(urlParam+1, url.length);
            event.preventDefault();
            var request = $.ajax({
                url: "ajax.php",
                method: "POST",
                data: {
                    imageName: urlSubStr,
                    perform: "addComment",
                    comment: $("input[name=commentField]").val(),
                    user: $(".userSession").val()
                }
            });
            request.done(function(){
                refreshListComments();
            })
        })

    })

    function refreshListComments(){
        var url = $(location).attr('href');
        var urlParam = url.indexOf("=");
        var urlSubStr = url.substr(urlParam+1, url.length);
        var request = $.ajax({
            url:"ajax.php",
            dataType: "html",
            method: "POST",
            data:{
                imageName: urlSubStr,
                perform: "displayComment",
            }
        });
        request.done(function(answer){
            console.log(answer);
            $(".commentDisplay").html(answer);
        })
    }
</script>
<body>
<?php
$aPath = ASSETSPATH;
include($aPath . "header.php");
require_once('config.inc.php');
$imgP = new ImageProcessing();
$auth = new Authentication();
foreach ($imgP->getImg() as $img):
if ($_GET['img'] == $img['image']):
?>
<div class="main-content">
    <div class="backbutton"><button onclick="history.go(-1);">🠔 </button></div>
    <div class="imgcontainer">
    <input class="userSession" type="hidden" value="<?=$username?>">
    <div class="imageOpen">
        <img src="./uploads/<?=$img['image']?>">
    </div>
        <div class ="infocontainer">
    <div class="imgInfo">
        <a class="imgPfp" href="profile.php?user=<?= $img['UserID']?>">
            <?php
            foreach ($auth->getUser() as $user):
                if($img['UserID'] == $user['ID']):
                    ?>
                    <img class="imagepfp" src="./profilepics/<?= $user['Profilepic'] ?>.jpg">
                <?php
                endif;
            endforeach;
            ?>
        </a>
        <a class="imgProfile" href="profile.php?user=<?= $img['UserID']?>">
        <?php
        foreach ($auth->getUser() as $user):
            if($img['UserID'] == $user['ID']):
                ?>
            <p><?=$user['Username']?></p>
        <?php
            endif;
        endforeach;
        ?>
        </a>
    </div>
        <div class="commentSection">
    <?php if(isset($_SESSION['loggedIn'])):?>
            <form class="commentForm" action="" method="POST">
                <input class="commentField" type="text" name="commentField" placeholder="Schreib ein Kommentar...">
                <input class="fertig" type="submit" value="Fertig">
            </form>
    <?php endif;?>
            <div class="commentDisplay"></div>
        </div>
        </div>

<?php
endif;
endforeach;
?>
    </div>
</div>
</body>
</html><?php

