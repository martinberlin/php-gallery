<?php
/**
 * Delete image
 * Date: 11.08.18
 */
require ("config.php");
$getFolder = isset($_GET['f']) ? $_GET['f'] : '';
$getFile = isset($_GET['i']) ? $_GET['i'] : '';

if (in_array($_SERVER['REMOTE_ADDR'], $adminRightIps)) {
   if (substr($getFolder,0,2) === '..') exit ('Not allowed folder path');	
   if (substr($getFile,0,2) === '..') exit ('Not allowed image path');	
   $deleteFile = $basedir.$getFolder.$getFile;

   if (unlink($deleteFile)) {
	   echo "File $getFile was deleted";
   } else {
	   echo "error deleting file";
   }
} else {
    $delete = false;
    echo "With your IP ".$_SERVER['REMOTE_ADDR']." is not allowed to delete files";
}


if ($delete) {
    echo "Image deleted";
}
