<?php
require 'config.php';
require 'gallery.class.php';

$getFolder = isset($_GET['f']) ? $_GET['f'] : '';
$gallery = new Gallery();
$gallery->setPath($basedir.$getFolder);
$images = $gallery->getImages($imageAllowedExtensions); //array of possible image extensions (useful if you have mixed galleries)
$row_counter = 0; //don't change that
$img_no_caption = " "; //default caption for image that don't have one

$page_title="Cam.local gallery"; //changes the <title> attribute AND the logo in top left corner
$no_images_warning="No images in gallery"; //Display the text when $gallery->setPath directory is empty.

