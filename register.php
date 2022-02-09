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
<?php
require_once("config.inc.php");
$aPath = ASSETSPATH;
include($aPath . "header.php");
?>
<div class="main-content">
    <div class="register-area">
        <form action="action.php" method="POST">
            Username: <br><input type="text" name="username" required><br>
            Password: <br><input type="password" name="password" required minlength="6"><br>
            Email: <br><input type="email" name="email" required>
            <br><input class="register-button" type="submit" name="register" value="REGISTER">
        </form>
        <?php
        if ($_GET['error'] == 1):
            ?>
            <script type="text/javascript" language="Javascript">
                alert("Name oder E-Mail schon vergeben!")</script>
        <?php
        endif;
        if ($_GET['registered'] == true):
            ?>
        <script type="text/javascript" language="Javascript">
            alert("Registrierung erfolgreich, du kannst dich jetzt anmelden!")</script>
        <?php
        endif;
        ?>
    </div>
</div>
</body>
    </html><?php