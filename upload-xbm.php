<?php
$debug = true;
if ($debug) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
require("uploadClass.php");
require("gallery/config.php");
$getFolder = isset($_GET['f']) ? $_GET['f'] : 'no_folder';
$getXbmThumb = (!isset($_GET['thumb']) || $_GET['thumb'] == 1) ? 1 : $_GET['thumb'];
$clientFolder = "{$getFolder}/";

if ((is_numeric($getXbmThumb) && $getXbmThumb<3) === false) {
    exit ("thumb parameter wrong");
}
// CONFIG
$thumb = array();
$thumb['width']  = 128; // Thumbnail max. width
$thumb['height'] = 64;  // 64  Thumbnail max. height
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
        //exit($directoryDate);
        mkdir($directoryDate);
    }
    $uploadedFile = $directoryDate.$uploadedName;
    $uploaded = move_uploaded_file($_FILES["upload"]["tmp_name"], $uploadedFile);

}

$imageObj = array();
if ($uploaded == false) {
    $xbm = UploadHelper::writeXbmMessage('Error uploading image', $thumb);
    $imageObj['xbm'] = UploadHelper::parseXbmToArray($xbm);
    $imageObj['url'] = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase."gallery/assets/error-uploading.png";
    exit (json_encode($imageObj));
}

try {
$im = new Imagick(realpath($uploadedFile));
$im->setCompressionQuality(79);
$im->resizeImage($thumb['width'], $thumb['height'], Imagick::FILTER_LANCZOS, 1, 1);
$im->quantizeImage(8,                        // Number of colors  8  (8/16 for depth 4)
    Imagick::COLORSPACE_GRAY, // Colorspace
    1,                        // Depth tree  16
    TRUE,                     // Dither
    FALSE);
} catch (ImagickException $e)
{
    $xbm = UploadHelper::writeXbmMessage('Imagemagick '.$e->getMessage(), $thumb);
    $imageObj['xbm'] = UploadHelper::parseXbmToArray($xbm);
    $imageObj['url'] = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase."gallery/assets/error-uploading.png";
    exit (json_encode($imageObj));
}

// Save the image
$im->setImageFormat('xbm'); //xbm
$im->writeimage("xbm/test.xbm");
// Populate imageObj before json encoding
// TODO Process imageBlob and parse xbm into json
$xbm = $im->getImageBlob();

if ($getXbmThumb) {
    switch ($getXbmThumb) {
        case 1:
            $cleanPixels = UploadHelper::parseXbmToArray($xbm, false); // Return int array (smaller response)
            break;
        case 2:
            $cleanPixels = UploadHelper::parseXbmToArray($xbm);
            break;
    }

    $imageObj['xbm'] = $cleanPixels;
    $imageObj['thumb_width'] = $im->getImageWidth();
    $imageObj['thumb_height'] = $im->getImageHeight();
}
$imageObj['url'] = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase.$directoryDate.$uploadedName;
$imageObj['hash']= md5_file($uploadedFile);
echo json_encode($imageObj);
