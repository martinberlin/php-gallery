<?php

// Directory where the pictures are uploaded
$basedir    = "../uploads/";
$page_title = "api.slosarek Cam uploads";

$imageAllowedExtensions = ['jpg','jpeg','png','JPG','PNG'];

// Thumbnails width / height
// Note: This w/h values are used on the cache name, if you change them all cache is going to be regenerated (But old ones will remain)
$thumbWidth = 400;
$thumbHeight = 400;
$thumbBestFit = true;
$thumbCroopZoom = true;
$thumbBaseDir    = "../thumbs/";
// Validity of the cache
$thumbCacheValidity = 240 * 60 * 60; // 240 * 3600s = 240 hours = 10 days -> Ater this thumbs will be regenerated
// IPs allowed to delete / upload
$adminRightIps = ['62.206.113.122','91.64.99.29','95.17.251.110','127.0.0.1'];
$col_md_x = 3; //Bootstrap - choose either 2,3,4 or 6 to have 6,4,3 or 2 pics per line respectively
//----------------------------------------------

$row_x = 12 / $col_md_x;
