<?php
class Gallery {
    public $path; //this will be later set as a variable in folder.php
    public function setPath($path) {
        $this->path = $path;
    }

    private function getDirectory($path) {
        return scandir($path);
    }

    public function getImages($extensions = array()) {
        $images = $this->getDirectory($this->path); //list all files
        $sort = array();

        foreach($images as $index => $image) {
            $explode = explode('.', $image);
            $extension = end($explode);
            if(!in_array($extension, $extensions)) { //check if files extensions meet the criteria set in folder.php
                unset($images[$index]);
            } else {
		    
		    $getPath = explode('/', $this->path);
		    $folderName = '';
		    foreach ($getPath as $pk => $pv) {
                      if ($pk < 2) continue;
                      $folderName .= $pv.'/';
                    }

                $imagePath = $this->path . '/' . $image;

                $exifDate = @exif_read_data($imagePath, 0, true)['IFD0']['DateTime'];
                $sort[$index] = $exifDate;
                $images[$index] = array( //make an array of images and corresponding miniatures
                    'full' => $imagePath,
		            'thumb' => 'thmbnailer.php?f='.$this->path . '&i=' . $image,
		            'folder' => $folderName,
		            'file' => $image,
                    'exifDate' => $exifDate
                    );
            }
           
        }
        // Sort per exif date
        array_multisort($sort, SORT_DESC, $images);

        return (count($images)) ? $images : false;   
    }

    public static function resizeImageImagick($thumbDir, $basedir, $folder, $imageFile, $width, $height, $filterType, $blur, $bestFit, $cropZoom)
    {
        $imagePath = $basedir.$folder.$imageFile;

        //The blur factor where > 1 is blurry, < 1 is sharp.
        $imagick = new \Imagick(realpath($imagePath));
        $imagick->setCompressionQuality(60);
        $imagick->resizeImage($width, $height, $filterType, $blur, $bestFit);

        $cropWidth = $imagick->getImageWidth();
        $cropHeight = $imagick->getImageHeight();

        if ($cropZoom) {
            $newWidth = $cropWidth / 2;
            $newHeight = $cropHeight / 2;

            $imagick->cropimage(
                $newWidth,
                $newHeight,
                ($cropWidth - $newWidth) / 2,
                ($cropHeight - $newHeight) / 2
            );

            $imagick->scaleimage(
                $imagick->getImageWidth() * 4,
                $imagick->getImageHeight() * 4
            );
        }

        $imagick->writeimage($thumbDir.$folder.$imageFile);

        header("Content-Type: image/jpg");
        echo $imagick->getImageBlob();
    }
}

