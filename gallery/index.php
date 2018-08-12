<?php
require "config.php";
include 'head.template.php'; ?>
<body>
<main role='main'><div class='container-fluid'>
  <div class="row">
 	<h2 style="text-align: center"><?=$page_title?></h2>

	<?php

	$row_counter = 1;

	$dir = new DirectoryIterator($basedir);


	foreach ($dir as $fileinfo) {

		if ($fileinfo->isDir() && !$fileinfo->isDot()) {

			$images = scandir($basedir.$fileinfo->getFilename());
			$imageUrl = $basedir.$fileinfo->getFilename().'/'.$images[2];
			// First and second have . .. in array

			//$img_caption = exif_read_data($image['full'], 0, true)['COMPUTED']['UserComment'];
			//$img_date = exif_read_data($image['full'], 0, true)['IFD0']['DateTime'];
			$img_caption = '';
			$img_date = '';
			//(!$img_caption) ? $img_caption = $img_no_caption : true;
			$row_counter++;
			?>
			<div class="col-md-<?php echo $col_md_x; ?>">
				<div class="picture_card">
					<h3><a href="folder.php?f=<?=$fileinfo->getFilename() ?>"><?=$fileinfo->getFilename() ?></a></h3>
					<a href="folder.php?f=<?=$fileinfo->getFilename() ?>"
					   data-title="<?php echo $fileinfo->getFilename(); ?>">
                        <img title="<?=$fileinfo->getFilename() ?>" src="<?=$imageUrl; ?>"
							 width="20%"></a>
					<br/>
					<div class="picture_card_description">
						<span class="glyphicon glyphicon-time"></span>&nbsp;<span
							class="picture_card_description_date"><?php echo $img_date; ?></span>

					</div>
				</div>
			</div>
			<?php
			if ($row_counter % $row_x == 0) {
				echo '</div><br /><div class="row">';
			}
		}
	}
	?>

  </div>	
</div>
</main>
</body>
</html>
