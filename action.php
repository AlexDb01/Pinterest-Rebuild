<?php
session_start();
require_once('config.inc.php');

//Authentifizieren & Registrieren
$auth = new Authentication();
$follow = new Follow();

if (isset($_POST['register'])) {
    if ($auth->registerCheck($_POST['username'], $_POST['email']) == true) {
        $pfp = "/Semesterprojekt/profileimgs/Default.jpg";
        $auth->register($_POST['username'], md5($_POST['password']), $_POST['email'], basename($pfp));
        foreach ($auth->getUser() as $user) {
            if ($_POST['username'] == $user['Username']) {
                $follow->addUser($user['ID']);


            }
        }
        header("Location: register.php?registered=true");
    } else if ($auth->registerCheck($_POST['username'], $_POST['email']) == false) {
        unset ($_SESSION['registered']);
        header("Location: register.php?error=1");
    }
}


if (isset($_POST['login'])) {
    $accepted = $auth->login($_POST['username'], md5($_POST['password']));

    if ($accepted) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $_POST['username'];
        header("Location: index.php");
    } else {
        unset($_SESSION['loggedIn']);
        header("Location: login.php?error=1");
    }
}
if (isset($_POST['logOut'])) {
    unset($_SESSION['loggedIn']);
    header("Location:index.php");
}


