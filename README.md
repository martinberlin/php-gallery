# PHP-Gallery
Simple PHP Bootstrap 4 gallery with thumbnail generator 

The thumbnailer uses Imagick but you can use any other (Gd)
This idea was born when I was looking for a very simple scripts to show the uploaded pictures from a WiFi camera automatically.
And the root is in [this Post](https://mindefrag.net/projects/php-gallery/).

In the spirit of respecting the original Bootstrap 4 layout I left everything as it was adding some additional functionality that I missed: 

   * Folder support
   * Thumbnailer generator with cache support (Using Image Magick)
   * Upload functionality
   * Delete a picture
   * Upload from WiFi hook (Using Arducam at the moment)
   * A simple config.php file where most of the configuration lives
 
### Installation notes
 
 Upload / Delete check your IP address to enable Admin rights. This is not using any User authentication or database.
 
 Make sure to give 0755 permissions and chown the uploads and thumbs directories to your Web server uses:
 
     mkdir uploads
     mkdir thumbs
     chmod 0755 uploads/
     chmod 0755 thumbs/
     sudo chown www-data:www-data uploads/
     sudo chown www-data:www-data thumbs/

PHP is supposed to be able to create directories and files there.
Change www-data to whatever user it is your apache or nginx server.

Thumbnail file validity is per default 10 days so if you happen to overwrite an image this is the time that the old thumbnail will be cached. Add any additional logic since it's left very raw with the intention not to overengineer the script.
And that's all ! Don't expect Flickr, the idea was to keep it as simple and uncomplicated as possible.
NOTE: In order to see something make sure to give this 2 folders the permissions to the apache user to write images so the thumbnails can be generated. Create some "Test" folder in uploads and copy some of your jpg images. 

### Requirements

Php 5+
php-imagick package installed: It uses http://php.net/manual/en/book.imagick.php to generate the thumbnails


#### Questions / Support 
https://fasani.de  Please leave an Issue if you find a bug and have fun with it.

source / license of original idea referred at the beginning of this repository: 
http://www.colorado.edu
