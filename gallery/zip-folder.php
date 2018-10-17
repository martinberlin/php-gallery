<?php
/**
 * Delete image
 * Date: 17.10.18
 */
require ("config.php");
$getFolder = isset($_GET['f']) ? $_GET['f'] : '';

if (in_array($_SERVER['REMOTE_ADDR'], $adminRightIps)) {
   if (preg_match("/\./", $getFolder)) exit('Not allowed folder path');	




} else {
    echo "With your IP ".$_SERVER['REMOTE_ADDR']." is not allowed to ZIP a folder";
}


