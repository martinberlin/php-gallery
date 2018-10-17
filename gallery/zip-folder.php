<?php
/**
 * Delete image
 * Date: 17.10.18
 * NOTE: I stealed this shamesly giving an upvote from here:
 * https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
 */
require ("config.php");
$getFolder = isset($_POST['f']) ? $_POST['f'] : '';

if (in_array($_SERVER['REMOTE_ADDR'], $adminRightIps)) {
   if (preg_match("/\./", $getFolder)) exit('Not allowed folder path');
    $rootPath = realpath( $basedir.$getFolder );

// Initialize archive object
    $zip = new ZipArchive();
    $zipName = str_replace('/', '_', $getFolder).'.zip';
    $zipFile = $basedir.$getFolder.'/'.$zipName;
    //echo " <a href='$zipfile'>Click here to download ".$zipfile."</a>";
    $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file)
    {
        // Skip directories and zip files (they would be added automatically)
        if (!$file->isDir() && $file->getExtension() !== 'zip')
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

// Zip archive will be created only after closing object
    try {
        $zip->close();
    } catch (Exception $exception) {
        exit("ERROR zipping file: "+ $exception->getMessage());
    }

    //then send the headers to force download the zip file
    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename=$zipName");
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile("$zipFile");

} else {
    echo "With your IP ".$_SERVER['REMOTE_ADDR']." is not allowed to ZIP a folder";
}


