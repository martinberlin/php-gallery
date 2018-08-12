<?php
/**
 * Delete image
 * Date: 11.08.18
 */
require ("config.php");
$getFile = isset($_GET['f']) ? $_GET['f'] : '';

if (in_array($_SERVER['REMOTE_ADDR'], $adminRightIps)) {
    $delete = unlink($getFile);
} else {
    $delete = false;
    echo "With your IP ".$_SERVER['REMOTE_ADDR']." is not allowed to delete files";
}


if ($delete) {
    echo "Image deleted";
}