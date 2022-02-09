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
    <link rel="stylesheet" media="screen" href="style.css" type="text/css"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans&display=swap" rel="stylesheet">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){

        refreshList();

        $(".upload").submit(function(event){
            event.preventDefault();
            var fd = new FormData();
            var images = $("input[type=file]")[0].files;
            var users = $("input[name=userID]").val();
            var username = $("input[name=username]").val();
            fd.append('image',images[0]);
            fd.append('perform','save');
            fd.append('user',users);
            fd.append('username',username);
            var request = $.ajax({
                url: "ajax.php",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false
            });
            request.done(function(answer){
                console.log(answer);
                refreshList();
            })
        });


    })

    function refreshList(){
        var request = $.ajax({
            url:"ajax.php",
            dataType: "html",
            method: "POST",
            data: {
                perform: "display"
            }
        });

        request.done(function(answer){
            console.log(answer);
            $(".image").html(answer);
        })
    }



</script>
<body class="headerbody">
<div class="header">
<header>
    <nav class="nav">
            <a class="homebutton" href="/Semesterprojekt/index.php">Home</a>
        <?php if(isset($_SESSION['loggedIn'])):?>
        <a class="myfollowing" href="./myfollowage.php">Folge Ich</a>
        <?php endif;?>
        <?php if(isset($_SESSION['loggedIn'])):?>
        <form class="upload" action="" method="POST" enctype="multipart/form-data">
            <label class="custom-file-upload">
            <input class="file" type="file" name="image_upload">
                Bild auswählen
            </label>
            <input class="imguploadbtn" type="submit" value="Bild Hochladen" name="upload">
            <input type="hidden" name="username" value="<?=$username?>">
            <?php
            $auth = new Authentication();
            foreach ($auth->getUser() as $user):
                if($user['Username'] == $username):
            ?>
            <input type="hidden" name="userID" value="<?= $user['ID']?>">
                <?php
                    endif;
                    endforeach;
                    ?>
        </form>
        <div class="dropdown">
            <?php
            foreach ($auth->getUser() as $user):
                if($username == $user['Username']):
                    ?>
                    <img class="profilePic" src="./profileimgs/<?=$user['Profilepic']?>">
                <?php
                endif;
                endforeach;
            ?>
            <div class="drpMenu">
                <a href="/Semesterprojekt/account.php?user=<?= $username ?>" class="myaccount">Mein Account</a>
                <form class= "logoutform"action="/Semesterprojekt/action.php" method="POST">
                    <input class="logoutbtn"  type="submit" name="logOut" value="Abmleden">
                </form>
            </div>
        </div>
        <?php endif;?>
        <?php if(!isset($_SESSION['loggedIn'])):?>
        <a class="loginlink" href="/Semesterprojekt/login.php" class="login">Anmelden</a>
            <a class="registerlink" href="/Semesterprojekt/register.php" class="register">Registrieren</a>
        <?php endif;?>
    </nav>
</header>
</div>
</body>
</html>


<?php
