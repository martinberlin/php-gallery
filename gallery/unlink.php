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
	   echo "File $getFile was deleted. ";
       // Delete also thumbnail
       $thumb = $thumbBaseDir.$getFolder.$getFile;
       unlink($thumb);
       // If there is no more images then delete also uploads and thumbs folder
       if (count(scandir($basedir.$getFolder)) == 2) {
           rmdir($basedir.$getFolder);
           rmdir($thumbBaseDir.$getFolder);
           $dirs = explode("/",$getFolder);
           echo "<b>Emty directory {$getFolder} was deleted you will be redirected to {$dirs[0]} in 3 seconds</b>";
           echo "<script>setTimeout(function(){ window.parent.location='folder.php?f={$dirs[0]}'; }, 3000);</script>";
       }
   } else {
	   echo "error deleting file";
   }
} else {
    $delete = false;
    echo "With your IP ".$_SERVER['REMOTE_ADDR']." is not allowed to delete files";
}


