<?php
require_once('config.inc.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

//Bilder hochladen & anzeigen
if ($_POST['perform'] == "save") {
    //----------------Benachrichtigungs-Mail-----------------------------
    $auth = new Authentication();
    $follow = new Follow();
    $mail = new PHPMailer(true);
    foreach ($follow->getFollow() as $f) {
        if ($f['Follows'] == $_POST['user']) {
            foreach ($auth->getUser() as $user) {
                if ($f['UserID'] == $user['ID']) {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'fh191133@gmail.com';
                    $mail->Password = 'cukziiidzqywqusb';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $recipient= [
                      ['email' => $user['Email'], 'name' => $user['Username']]
                    ];
                    foreach($recipient as $r) {
                        $mail->setFrom('fh191133@gmail.com', 'Mr. Email');
                        $mail->addAddress($r['email'], $r['name']);

                        $mail->isHTML(true);
                        $mail->Subject = 'Neues Bild von ' . $_POST['username'];
                        $mail->Body ='Hey '. $r['name'] .' '. $_POST['username'] . ' Hat ein neues Bild hochgeladen! Zu ' . $_POST['username'] . 's Profil: http://mt191133.students.fhstp.ac.at/Semesterprojekt/profile.php?user=' . $_POST['user'];
                        $mail->AltBody ='Hey '. $r['name'] .' '. $_POST['username'] . ' Hat ein neues Bild hochgeladen! Zu ' . $_POST['username'] . 's Profil: http://mt191133.students.fhstp.ac.at/Semesterprojekt/profile.php?user=' . $_POST['user'];
                    }
                }

            }

        }

    }

//-----------------------------------------------------------------------------------

    $imgP = new ImageProcessing();
    $name = $_FILES['image']['name'];
    $path_parts = pathinfo($name);
    $ext = strtolower($path_parts['extension']);
    $ext_array = array("jpg", "jpeg", "png", "gif");
    $savedName = "img" . random_int(11111, 999999) . "." . $ext;

    if (in_array($ext, $ext_array)) {

        saveImage($_FILES['image']['tmp_name'], $savedName);
        $imgP->imgDbUpload($_POST['user'], $savedName, $name);
        $mail->send();
    }

}

if ($_POST['perform'] == "savePfp") {
    $auth = new Authentication();
    $imgP = new ImageProcessing();
    $name = $_FILES['pfpImage']['name'];
    $path_parts = pathinfo($name);
    $ext = strtolower($path_parts['extension']);
    $ext_array = array("jpg", "jpeg", "png", "gif");
    $savedName = "pfp" . random_int(11111, 999999) . "." . $ext;

    if (in_array($ext, $ext_array)) {

        savePfp($_FILES['pfpImage']['tmp_name'], $savedName);
        $imgP->changePfP($savedName, $_POST['user']);

    }

}


if($_POST['perform'] == "display"){
    $imgP = new ImageProcessing ();
    foreach($imgP->getImg() as $img):
        ?>
        <a href="image.php?img=<?=$img['image']?>">
            <img src="./thumbnails/<?=$img['image']?>.jpg">
        </a>

    <?php
    endforeach;
}


//Profilbild hochladen & anzeigen
if ($_POST['perform'] == "displayPfp") {
    $auth = new Authentication();
    foreach ($auth->getUser() as $pfp):
        if ($pfp['Username'] == $_POST['user']):
            ?>
            <img src="./profilepics/<?= $pfp['Profilepic'] ?>.jpg">
        <?php
        endif;
    endforeach;
}


if ($_POST['perform'] == "Folgen") {
    $follow = new Follow();
    $auth = new Authentication();
    foreach ($auth->getUser() as $user) {
        if ($_POST['user'] == $user['Username']) {
            foreach ($follow->getFollow() as $f) {
                if ($user['ID'] == $f['UserID']) {
                    if ($_POST['profile'] == $f['Follows'] && $f['Status'] == 0) {
                        $follow->updateState($user['ID'], $_POST['profile'], 1);
                    } elseif ($f['Follows'] !== $_POST['profile'] && $f['Status'] == 0) {
                        $follow->updateFollow($user['ID'], $_POST['profile']);
                    } elseif ($f['Follows'] !== $_POST['profile'] && $f['Status'] == 1) {
                        $follow->follow($user['ID'], $_POST['profile'], 1);
                    }
                }
            }
        }

    }

}

if ($_POST['perform'] == "Entfolgen") {
    $follow = new Follow();
    $auth = new Authentication();
    foreach ($auth->getUser() as $user) {
        if ($_POST['user'] == $user['Username']) {
            foreach ($follow->getFollow() as $f) {
                if ($user['ID'] == $f['UserID']) {
                    if ($_POST['profile'] == $f['Follows'] && $f['Status'] == 1) {
                        $follow->updateState($user['ID'], $_POST['profile'], 0);
                    }
                }
            }
        }
    }
}


if ($_POST['perform'] == "state") {
    $auth = new Authentication();
    $follow = new Follow();
    foreach ($auth->getUser() as $user):
        if ($_POST['session'] == $user['Username']):
            foreach ($follow->getFollow() as $f):
                if ($user['ID'] == $f['UserID']):
                    if ($_POST['profile'] !== $f['Follows'] && $f['Status'] == 0):
                        ?>
                        <input type="submit" name="fbutton" value="Folgen">
                        <input type="hidden" name="usersession" value="<?= $_POST['session'] ?>">
                        <input type="hidden" name="profile" value="<?= $_POST['profile'] ?>">

                    <?php
                    elseif ($_POST['profile'] == $f['Follows']):
                        if ($f['Status'] == 0):
                            ?>
                            <input type="submit" name="fbutton" value="Folgen">
                            <input type="hidden" name="usersession" value="<?= $_POST['session'] ?>">
                            <input type="hidden" name="profile" value="<?= $_POST['profile'] ?>">

                        <?php
                        elseif ($f['Status'] == 1):
                            ?>
                            <input type="submit" name="fbutton" value="Entfolgen">
                            <input type="hidden" name="usersession" value="<?= $_POST['session'] ?>">
                            <input type="hidden" name="profile" value="<?= $_POST['profile'] ?>">

                        <?php
                        endif;
                    endif;
                endif;
            endforeach;
        endif;
    endforeach;
}

if ($_POST['perform'] == "addComment") {
    $auth = new Authentication();
    $imgP = new ImageProcessing();
    $comments = new Commentsection();

    foreach ($auth->getUser() as $user) {
        if ($user['Username'] == $_POST['user']) {
            foreach ($imgP->getImg() as $img) {
                if ($img['image'] == $_POST['imageName']) {
                        $comments->addComment($user['ID'], $img['ID'], $_POST['comment']);
                }
            }
        }
    }

}

if($_POST['perform']=="displayComment"){
    $comments = new Commentsection();
    $auth = new Authentication();
    $imgP = new ImageProcessing();

    foreach($imgP->getImg() as $img):
        if($img['image'] == $_POST['imageName']):
            foreach($comments->getComments() as $c):
                if($c['ImageID'] == $img['ID']):
                    foreach($auth->getUser() as $user):
                        if($user['ID'] == $c['UserID']):
                            ?>
                            <div class="commentInfo">
                                <div class="commentUser">
                                    <a href="profile.php?user=<?= $user['ID'] ?>">
                                        <img class="commentPfp" src="./profileimgs/<?= $user['Profilepic'] ?>">
                                    </a>
                                    <a href="profile.php?user=<?= $user['ID'] ?>">
                                        <p class="commentUser"><?= $user['Username'] ?></p>
                                    </a>
                                </div>
                                <p class="commentText"><?= $c['text'] ?></p>
                            </div>
                        <?php
                        endif;
                    endforeach;
                endif;
            endforeach;
        endif;
    endforeach;

}




