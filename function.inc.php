<?php
spl_autoload_register(function ($class){
    require_once 'classes/'.$class.'.class.php';
});


function loadImage($file){
    $path_parts = pathinfo($file);
    $ext = strtolower($path_parts['extension']);

    if($ext == "jpg" || $ext == "jpeg"){
        return imagecreatefromjpeg($file);
    }elseif ($ext == "png"){
        return imagecreatefrompng($file);
    }elseif ($ext == "gif"){
        return imagecreatefromgif($file);
    }else{
        return imagecreatefromgif("http://ptktemper.students.fhstp.ac.at/mt19/gd/keinbild.gif");
    }

}


function resizePic($src,$maxsize,$path){

    $imgsrc = loadImage($src);
    $height = imagesy($imgsrc);
    $width = imagesx($imgsrc);

    if($width > $height){
        $width_new = $maxsize;
        $height_new = ($height/$width)*$maxsize;
    }

    if($height >= $width){
        $height_new = $maxsize;
        $width_new = ($width/$height)*$maxsize;
    }

    $thumb = imagecreatetruecolor($width_new, $height_new);

    imagecopyresampled($thumb, $imgsrc,0,0,0,0,$width_new, $height_new, $width, $height);

    imagejpeg($thumb, $path.basename($src).".jpg");

}

function resizePfp($src,$path){

    $imgsrc = loadImage($src);
    $height = imagesy($imgsrc);
    $width = imagesx($imgsrc);
    if($width > $height) {
        $new_height =   $height;
        $new_width  =   floor($width * ($new_height / $height));
        $crop_x     =   ceil(($width - $height) / 2);
        $crop_y     =   0;
    }
    else {
        $new_width  =   $width;
        $new_height =   floor( $height * ( $new_width / $width ));
        $crop_x     =   0;
        $crop_y     =   ceil(($height - $width) / 2);
    }

    $thumb = imagecreatetruecolor($width, $height);

    imagecopyresampled($imgsrc, $thumb ,0,0, $crop_x, $crop_y, $new_width, $new_height, $width, $height);

    imagejpeg($thumb, $path.basename($src).".jpg");

}

function saveImage($tmp_path, $filename){
    move_uploaded_file($tmp_path, UPLOADPATH.$filename);
    resizePic(UPLOADPATH.$filename, 300, THUMBPATH);
}

function savePfp($tmp_path, $filename){
    move_uploaded_file($tmp_path, PFPPATH.$filename);
    resizePic(PFPPATH.$filename, 250, PFPPATH2);
}




