<?php
/**
 * Created by http://fasani.de following Imagick example
 * Date: 11.08.18
 * Time: 21:16
 */
require "config.php";
require "gallery.class.php";

$folder = isset($_GET['f']) ? $_GET['f'] : null;
$imageFile = isset($_GET['i']) ? $_GET['i'] : null;
if (is_null($imageFile)) exit('Image not received');
if (is_null($folder)) exit('Folder not received');

$getPath = explode('/', $folder);
$folderName = '';
foreach ($getPath as $pk => $pv) {
    if ($pk < 2) continue;
    $folderName .= $pv.'/';
}
$thumb = $thumbBaseDir.$folderName.$imageFile;
if (!is_dir($thumbBaseDir.$folderName)) {
    mkdir($thumbBaseDir.$folderName, 0755, true);
}


if(file_exists($thumb) && filectime($thumb) > time() - $thumbCacheValidity) {
    // cache is valid
    $file = file_get_contents($thumb);
    header("Content-Type: image/jpg");
    exit($file);
} else {

  // Generate new thumbnal. Note playing with               v bestFit / cropZomm gives out nice effects
    Gallery::resizeImageImagick($thumbBaseDir, $basedir, $folderName, $imageFile, $thumbWidth ,$thumbHeight, 0, 1, $thumbBestFit, $thumbCroopZoom);
}


