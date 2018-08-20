<?php
$directoryBase = "camera-uploads/";
$uploadBase = "uploads/";

if ($_FILES["upload"]["error"] > 0)
{
    throw new UploadException($_FILES['upload']['error']);
} else {
    $fileName = $_FILES["upload"]["name"];
    $explodeFile = explode(".", $fileName);
    $extension = end($explodeFile);
    $directoryDate = $rootBase . date('Y-m-d') . "/";
    $uploadedName = date('l-H-i-s').".".$extension;

// Check if directory exists if not create it
        if (!is_dir($directoryDate)) {
            mkdir($directoryDate);
        }
        $uploaded = move_uploaded_file($_FILES["upload"]["tmp_name"], $directoryDate.$uploadedName);

    if ($uploaded) {
        $imageLink = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase.$directoryDate.$uploadedName;
    } else {
        $imageLink = "http://".$_SERVER['HTTP_HOST']."/".$directoryBase."gallery/assets/error-uploading.png";
    }
    echo $imageLink;
}


class UploadException extends Exception
{
    public function __construct($code) {
        $message = $this->codeToMessage($code);
        parent::__construct($message, $code);
    }

    private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }
}
