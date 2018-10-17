<?php require 'folder-gallery.php'; ?>
<?php include 'views/head.template.php'; ?>
<body>
<?php include 'views/navigation.template.php';
function humanFilesize($bytes, $decimals = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
$totalImages = ($images) ? count($images) : 0;
?>

<main role='main'><div class='container-fluid'>
		<? if ($totalImages) {
			echo $totalImages." images";
		} ?>

  <div class="row">
	<?php if($images):

       foreach($images as $image):
	    try {
			$imgInfo = getimagesize($image['full']);
			$mime = explode('/', $imgInfo['mime']);
			$img['w'] = $imgInfo[0];
			$img['h'] = $imgInfo[1];
			$img['m'] = $mime[1];
			$img['s'] = humanFilesize(filesize($image['full']));

			$img['c'] = @exif_read_data($image['full'], 0, true)['COMPUTED']['UserComment'];
		} catch (Exception $e) {
			$img['c'] = '';
		}

		    $row_counter++;
		    ?>
		    <div class="col-md-<?php echo $col_md_x; ?>">

		    	<a class="gallery" href="<?php echo $image['full']; ?>"
            data-title="<?=$image['file'] ?>">
					<img src="<?= $image['thumb']; ?>" width="100%"></a>

                <br />

			      	<div>
						<a href="unlink.php?f=<?=$image['folder']?>&i=<?=$image['file']?>"
						    target="frame" title="Delete" style="color:darkred" onclick="$(this).parent().parent().invisible()">
							<span class="glyphicon glyphicon-remove-circle"></span></a>
						<a href="<?= $image['full']; ?>"><span class="glyphicon glyphicon-download" title="Download"></span></a>

			    		<span class="glyphicon glyphicon-time" title="Jpeg timestamp" ></span>&nbsp;
						<span class="picture_card_description_date"><?= $image['exifDate']; ?></span>
						<small><?=$img['w'].'x'.$img['h'].' '.$img['m'].' '.$img['s'] ?><br>
							<?= $img['c'] ?></small>

			    

			      	</div>

		    </div>
		    <?php
		    if ($row_counter % $row_x == 0) {
		    echo '</div><br /><div class="row">';
		    }  
		    endforeach; ?>
	    
		<?php else: ?>
	  <div class="col-md-12">
		<div id="no_images">
			<?php
			$location = $basedir.$getFolder;

			$dirIterator = new DirectoryIterator($location);
			foreach ($dirIterator as $fileinfo) {
				$sorted_keys[$fileinfo->getMTime()] = $fileinfo->key();
			}
			krsort($sorted_keys);

			if (iterator_count($dirIterator)) {
				echo "<h1>Listing $getFolder directory:</h1>";
				/* Iterate `DirectoryIterator` as a sorted array */
				foreach ( $sorted_keys as $key )
				{
					$dirIterator->seek($key);
					$fileinfo = $dirIterator->current();

					if ($fileinfo->isDir() && !$fileinfo->isDot()) {
						$subDir = $fileinfo->getFilename();
						echo "<h2><a href='folder.php?f={$getFolder}/{$subDir}'>" . $subDir . "</a></h2>";
					}
				}
			} else {
			   echo $no_images_warning;
			}
			?>
		</div>
	  </div>
	<?php endif; ?>
	
  </div>
	<div class="row">
	<form enctype="multipart/form-data" action="upload.php"  target="frame" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
		<div class="col-md-12" id="image" lang="en">
			Folder: <input name="folder" type="text" value="<?=$getFolder?>">
			<input type="file" name="image" id="uploadImage" aria-describedby="fileHelp">

			<input type="submit" value="Upload" class="btn btn-dark" value="Upload" />
		</div>
	</form>
	</div>
	<iframe name="frame" class="col-md-12" onload="resizeIframe(this)"></iframe>
</div>
</main>

<script>
	function resizeIframe(obj) {
		console.log("Resizing frame to height: "+obj.contentWindow.document.body.scrollHeight);
		obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
	}

    $(document).ready(function() {
        $(".gallery").colorbox({
			rel: 'gallery',
			slideshow: true,
			slideshowAuto: false,
			slideshowSpeed: 500,
			opacity:0.95});
    });
	jQuery.fn.visible = function() {
		return this.css('visibility', 'visible');
	};

	jQuery.fn.invisible = function() {
		return this.css('visibility', 'hidden');
	};
</script>
</body>
</html>
