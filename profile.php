<?php session_start();
require_once('config.inc.php');
$username = $_SESSION['username'];
$auth = new Authentication();
foreach ($auth->getUser() as $user) {
    if ($username == $user['Username'] && $_GET['user'] == $user['ID']) {
        header("Location: account.php?user=$username");
    }
}
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
        refreshListFollow();

        $(".followButton").submit(function (event) {
            event.preventDefault();
            var request = $.ajax({
                url: "ajax.php",
                method: "POST",
                data: {
                    perform: $("input[name=fbutton]").val(),
                    user: $("input[name=usersession]").val(),
                    profile: $("input[name=profile]").val()
                }
            });
            request.done(function () {
                refreshListFollow();
            });
        });
    })


    function refreshListFollow() {
        var url = $(location).attr('href');
        var urlParam = url.indexOf("=");
        var urlSubStr = url.substr(urlParam + 1, url.length);
        var request = $.ajax({
            url: "ajax.php",
            dataType: "html",
            method: "POST",
            data: {
                profile: urlSubStr,
                perform: "state",
                session: $(".userSession").val()
            }
        });
        request.done(function (answer) {
            console.log(answer);
            $(".followButton").html(answer);
        })
    }
</script>
<body>
<input class="userSession" type="hidden" value="<?= $username ?>">
<?php
$aPath = ASSETSPATH;
include($aPath . "header.php");
?>
<div class="main-content">
    <div class="accInfo">

<?php
foreach ($auth->getUser() as $user):
    if ($_GET['user'] == $user['ID']):
        ?>
        <h2> <?= $user['Username'] ?> </h2>
        <img class="pfpImgp" src="./profilepics/<?= $user['Profilepic'] ?>.jpg">
    <?php
    endif;
endforeach;
?>
<?php if (isset($_SESSION['loggedIn'])): ?>
    <form class="followButton" method="POST">

    </form>
    </div>
    <div class="accountImgs">
<?php
endif;
$imgP = new ImageProcessing();
foreach ($imgP->getImg() as $img):
if ($_GET['user'] == $img['UserID']):
?>
<a href="image.php?img=<?= $img['image'] ?>">
    <img src="./thumbnails/<?= $img['image'] ?>.jpg">
    <?php
    endif;
    endforeach;
    ?>
</div>
</div>
</body>
    </html><?php
