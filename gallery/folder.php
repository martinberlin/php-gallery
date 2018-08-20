<?php require 'folder-gallery.php'; ?>
<?php include 'head.template.php'; ?>
<body>
<?php include 'navigation.template.php'; ?>

<main role='main'><div class='container-fluid'>
  <div class="row">
	<?php if($images): ?>

	    <?php foreach($images as $image):
	    try {
			$img_caption = @exif_read_data($image['full'], 0, true)['COMPUTED']['UserComment'];
		} catch (Exception $e) {
			$img_caption = '';
		}

		    $row_counter++;
		    ?>
		    <div class="col-md-<?php echo $col_md_x; ?>">
		      <div class="picture_card">
		    	<a href="<?php echo $image['full']; ?>" data-lightbox="roadtrip" 
            data-title="<?php echo $img_caption; ?>"><img title="<?php echo $img_caption; ?>" 
            src="<?= $image['thumb']; ?>" width="100%"></a>
		    	<br />
			      	<div class="picture_card_description">

						<a href="unlink.php?f=<?=$image['folder']?>&i=<?=$image['file']?>" target="frame" title="delete" style="color:darkred">
							<span class="glyphicon glyphicon-remove-circle"></span></a>
						<a href="<?= $image['full']; ?>"><span class="glyphicon glyphicon-download"></span></a>

			    		<span class="glyphicon glyphicon-time"></span>&nbsp;
						<span class="picture_card_description_date"><?= $image['exifDate']; ?></span>

			    	<br />
			    
				    <?php if ($img_caption == $img_no_caption) {
				      echo "";
				    } else {
				      echo $img_caption;
				    }?>
			      	</div>
		      </div>
		    </div>
		    <?php
		    if ($row_counter % $row_x == 0) {
		    echo '</div><br /><div class="row">';
		    }  
		    endforeach; ?>
	    
		<?php else: ?>
		<div id="no_images"><?php echo $no_images_warning; ?></div>

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
	<iframe name="frame" class="col-md-10"></iframe>
</div>
</main>

</body>
</html>
