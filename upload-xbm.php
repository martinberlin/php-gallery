<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 04.11.18
 * Time: 20:23
 */
// Create a blank image and add some text
//$im = imagecreatetruecolor(128, 64);
$im = imagecreatefromjpeg('xbm/3.jpg');
$imgw = imagesx($im);
$imgh = imagesy($im);
for ($i=0; $i<$imgw; $i++)
{
    for ($j=0; $j<$imgh; $j++)
    {

        // Get the RGB value for current pixel

        $rgb = imagecolorat($im, $i, $j);

        // Extract each value for: R, G, B

        $rr = ($rgb >> 16) & 0xFF;
        $gg = ($rgb >> 8) & 0xFF;
        $bb = $rgb & 0xFF;

        // Get the value from the RGB value

        $g = round(($rr + $gg + $bb) / 3);

        // Gray-scale values have: R=G=B=G

        //$g = (r + g + b) / 3
        if($g> 0x7F) //you can also use 0x3F 0x4F 0x5F 0x6F its on you
            $g=0xFF;
        else
            $g=0x00;



        $val = imagecolorallocate($im, $g, $g, $g);

        // Set the gray value

        imagesetpixel ($im, $i, $j, $val);
    }
}
/** Alternative:
imagefilter($img, IMG_FILTER_GRAYSCALE); //first, convert to grayscale
imagefilter($img, IMG_FILTER_CONTRAST, -255); //then, apply a full contrast
imagejpeg($img);
 */
// Save the image
imagexbm($im, 'xbm/3.xbm');

imagedestroy($im);