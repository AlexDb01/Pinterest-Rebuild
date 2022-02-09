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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        refreshListImgDet();
    })

    function refreshListImgDet(){
        var request = $.ajax({
            url:"ajax.php",
            dataType: "html",
            method: "POST",
            data: {
                perform: "displayImgDet"
            }
        });

        request.done(function(answer){
            console.log(answer);
            $(".imgD").html(answer);
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
    <div class="image">
    </div>
    <div class="imgD"></div>
</div>
</body>
</html><?php
