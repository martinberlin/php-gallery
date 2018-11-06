<?php
require("uploadClass.php");
$getFolder = isset($_GET['f']) ? $_GET['f'] : 'no_folder';
$clientFolder = "{$getFolder}/";

// CONFIG
$thumb = array();
$thumb['width']  = 128;
$thumb['height'] = 64;
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
        //exit($directoryDate);
        mkdir($directoryDate);
    }
    $uploadedFile = $directoryDate.$uploadedName;
    $uploaded = move_uploaded_file($_FILES["upload"]["tmp_name"], $uploadedFile);

}

if ($uploaded == false) {
    $imageLink = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase."gallery/assets/error-uploading.png";
    exit($imageLink);
}
$imageObj = array();



// Create a blank image and add some text
//$im = imagecreatetruecolor(128, 64);
//$im = imagecreatefromjpeg('xbm/3.jpg');
$im = new \Imagick(realpath($uploadedFile));
$im->setCompressionQuality(99);
$im->resizeImage($thumb['width'], $thumb['height'], Imagick::FILTER_LANCZOS, 1, 0);

$im->quantizeImage(8,                        // Number of colors  8  (8/16 for depth 4)
    Imagick::COLORSPACE_GRAY, // Colorspace
    1,                        // Depth tree  16
    TRUE,                     // Dither
    FALSE);

/** Alternative:
imagefilter($img, IMG_FILTER_GRAYSCALE); //first, convert to grayscale
imagefilter($img, IMG_FILTER_CONTRAST, -255); //then, apply a full contrast
imagejpeg($img);
 */
// Save the image
$im->setImageFormat('xbm'); //xbm
$im->writeimage("xbm/test.xbm");
// Populate imageObj before json encoding
// TODO Process imageBlob
$imageObj['xbm'] = $im->getImageBlob();

$imageObj['thumb'] = array(
    'width' => $thumb['width'],
    'height' => $thumb['height']
    );

$imageObj['url'] = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase."gallery/assets/error-uploading.png";
echo json_encode($imageObj);