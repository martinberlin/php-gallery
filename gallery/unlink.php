<?php
/**
 * Delete image
 * Date: 11.08.18
 */
require ("config.php");
$getFolder = isset($_GET['f']) ? $_GET['f'] : '';
$getFile = isset($_GET['i']) ? $_GET['i'] : '';

if (in_array($_SERVER['REMOTE_ADDR'], $adminRightIps)) {
   if (preg_match("/\./", $getFolder)) exit('Not allowed folder path');	
   if (preg_match("/\.\./", $getFile) || $getFile === '') exit('Not allowed file path');	
   
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


