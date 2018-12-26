<?php
$debug = true;
if ($debug) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
require("uploadClass.php");
require("gallery/config.php");
$getFolder = isset($_GET['f']) ? $_GET['f'] : 'no_folder';
$getXbmThumb = (!isset($_REQUEST['thumb']) || $_REQUEST['thumb'] == 1) ? 1 : $_REQUEST['thumb'];
$clientFolder = "{$getFolder}/";

if ((is_numeric($getXbmThumb) && $getXbmThumb<4) === false) {
    exit ("thumb parameter wrong");
}
// CONFIG
$thumb = array();
$thumb['width']  = 128;     // Thumbnail max. width
$thumb['height'] = 64;      // 64  Thumbnail max. height
$thumb['height_jpg'] = 50; // TFT 7735
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

    if ($getXbmThumb == 3) {
        $im->resizeImage($thumb['width'], $thumb['height_jpg'], Imagick::FILTER_LANCZOS, 1, 1);
    } else {
        $im->resizeImage($thumb['width'], $thumb['height'], Imagick::FILTER_LANCZOS, 1, 1);
        $im->quantizeImage(8,                        // Number of colors  8  (8/16 for depth 4)
            Imagick::COLORSPACE_GRAY, // Colorspace
            1,                        // Depth tree  16
            TRUE,                     // Dither
            FALSE);
    }

} catch (ImagickException $e)
{
    $xbm = UploadHelper::writeXbmMessage('Imagemagick '.$e->getMessage(), $thumb);
    $imageObj['xbm'] = UploadHelper::parseXbmToArray($xbm);
    $imageObj['url'] = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase."gallery/assets/error-uploading.png";
    exit (json_encode($imageObj));
}

// Generate thumbnai

if ($getXbmThumb) {
    switch ($getXbmThumb) {
        // XBM int array (smaller response)
        case 1:
            $im->setImageFormat('xbm'); //xbm
            $im->writeimage("xbm/test.xbm");
            $xbm = $im->getImageBlob();
            $cleanPixels = UploadHelper::parseXbmToArray($xbm, false);
            $imageObj['xbm'] = $cleanPixels;
            $imageObj['thumb_width'] = $im->getImageWidth();
            $imageObj['thumb_height'] = $im->getImageHeight();
            break;
        // XBM default format (double size response)
        case 2:
            $im->setImageFormat('xbm'); //xbm
            $im->writeimage("xbm/test.xbm");
            $xbm = $im->getImageBlob();
            $cleanPixels = UploadHelper::parseXbmToArray($xbm);
            $imageObj['xbm'] = $cleanPixels;
            $imageObj['thumb_width'] = $im->getImageWidth();
            $imageObj['thumb_height'] = $im->getImageHeight();
            break;
        // JPG bytes
        case 3:
            $im->setImageFormat('jpg');
            $jpg = $im->getImageBlob();
            $cleanPixels = array();
            foreach(str_split($jpg) as $char){
                array_push($cleanPixels, ord($char)); // For HEX image: "0x".strtoupper(bin2hex($char))
            }
            //header("Content-Type: image/jpeg");exit($jpg); // Check JPEG
            //exit(implode(",", $cleanPixels)); // DEBUG
            $imageObj['jpg']    = $cleanPixels;
            break;
    }
}
$imageObj['url'] = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase.$directoryDate.$uploadedName;
$imageObj['hash']= md5_file($uploadedFile);
$imageObj['folder'] = $getFolder;
echo json_encode($imageObj);
