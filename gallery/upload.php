<?php
/**
 * Created by https://fasani.de
 * Date: 11.08.18
 */
error_reporting(E_ALL);
require "config.php";
$uploadDir = $basedir.$_POST['folder'].'/';
$fileName = basename($_FILES['image']['name']);
$uploadfile = $uploadDir.basename($fileName);

echo '<pre>';
if (in_array($_SERVER['REMOTE_ADDR'], $adminRightIps)) {
    // Check if directory exist:
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
        echo "/$uploadDir created";
    }
    try {
	$moveFile =  move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);
        if ($moveFile) {
           echo "File: $fileName was successfully uploaded.\n";
          } else {
           echo "File was not uploaded<br>";
           print_r(error_get_last()); print_r($_FILES['image']);
          }
    } catch (Exception $exception) {
        echo "Possible file upload attack!\n";
        echo($exception->getMessage());print_r($exception->getTrace());
    }
} else {

        echo "With your IP ".$_SERVER['REMOTE_ADDR']." is not allowed to upload files";
    }
print "</pre>";
