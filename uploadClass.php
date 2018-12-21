<?php
/**
 * Created by: martin
 * Date: 06.11.18
 */

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

class UploadHelper {
    /**
     * @param $message
     * @param $thumb
     * @return bool|string
     */
    static function writeXbmMessage($message, $thumb) {
        $im = imagecreatetruecolor($thumb['width'], $thumb['height']);
        $textColor = imagecolorallocate($im, 255, 255, 255);
        imagestring($im, 1, 5, 5,  $message, $textColor);
        imagexbm($im, 'xbm/error.xbm');
        $xbmContent = file_get_contents('xbm/error.xbm');
        return $xbmContent;
    }

    /**
     * Parse XBM image and extract the pixels into an array of HEX pixels
     * @param $xbmBlob
     * @return array
     */
    static function parseXbmToArray($xbmBlob, $hexa = true) {
        $pattern = "#{(.*?)}#s";
        preg_match($pattern, $xbmBlob, $matches);
        $explodedPixels = explode(",", $matches[1]);
        array_pop($explodedPixels);
        // Iterate and cleanup whitespaces and \n
        $cleanPixels = array();
        foreach ($explodedPixels as $pixel) {
            $pixel = preg_replace('/\s+/', '', $pixel);
            if (!$hexa) {
                $cleanPixels[] = hexdec($pixel);
                continue;
            }
            $cleanPixels[] = $pixel;
        }
        return $cleanPixels;
    }

}
