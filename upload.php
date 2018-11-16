<?php
require("uploadClass.php");
$getFolder = isset($_GET['f']) ? $_GET['f'] : 'no_folder';
$getRotation = isset($_GET['r']) ? $_GET['r'] : 0;         // Rotation angle

$clientFolder = "{$getFolder}/";

$directoryBase = "camera-uploads/";
$uploadBase = "uploads/".$clientFolder;

if ($_FILES["upload"]["error"] > 0)
{
    throw new UploadException($_FILES['upload']['error']);
} else {
    $fileName = $_FILES["upload"]["name"];
    $explodeFile = explode(".", $fileName);
    $extension = end($explodeFile);
    $directoryDate = $uploadBase . date('Y-m-d') . "/";
    $uploadedName = date('l-H-i-s').".".$extension;

// Check if directory exists if not create it
        if (!is_dir($directoryDate)) {
            mkdir($directoryDate);
        }
        $uploaded = move_uploaded_file($_FILES["upload"]["tmp_name"], $directoryDate.$uploadedName);

    if ($uploaded) {
        if ($getRotation) {
            $image = new Imagick($directoryDate.$uploadedName);
            $image->rotateImage ( 'white', $getRotation );
            $image->writeImage();
        }
        $imageLink = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase.$directoryDate.$uploadedName;
    } else {
        $imageLink = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase."gallery/assets/error-uploading.png";
    }
    echo $imageLink;
}