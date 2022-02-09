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
    <div class="lr-forms">
        <div class="login-area">
            <form action="action.php" method="POST">
                Nutzername: <br><input type="text" name="username" required><br>
                Passwort: <br><input type="password" name="password" required>
                <br><input class="login-button" type="submit" name="login" value="LOGIN">
            </form>
            <?php
            if($_GET['error'] == 1):
                ?>
                <script type="text/javascript" language="Javascript">
                    alert("Nutzername oder Passwort nicht Korrekt!")</script>
            <?php
                endif;
            ?>
        </div>
    </div>
</div>
</body>
</html><?php
