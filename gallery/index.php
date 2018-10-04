<?php
require "config.php";
include 'head.template.php'; ?>
<body>
<main role='main'><div class='container-fluid'>
  <div class="row">

	<?php

	$row_counter = 1;

	$dirIterator = new DirectoryIterator($basedir);
	foreach ($dirIterator as $fileinfo) {
		$sorted_keys[$fileinfo->getMTime()] = $fileinfo->key();
	}
	krsort($sorted_keys);

	/* Iterate `DirectoryIterator` as a sorted array */
	foreach ( $sorted_keys as $key )
	{
		$dirIterator->seek($key);
		$fileinfo = $dirIterator->current();

		if ($fileinfo->isDir() && !$fileinfo->isDot()) {

			$images = scandir($basedir.$fileinfo->getFilename());
			$imageUrl = $thumbBaseDir.$fileinfo->getFilename().'/'.$images[2];
			// First and second have . .. in array
			$row_counter++;
			$explodeThumb = explode(".", $imageUrl);
			?>
			<div class="col-md-<?php echo $col_md_x; ?>">
				<?php if (count($explodeThumb)>3) { ?>
				<div class="picture_card">
					<h3><a href="folder.php?f=<?=$fileinfo->getFilename() ?>"><?=$fileinfo->getFilename() ?></a></h3>
					<a href="folder.php?f=<?=$fileinfo->getFilename() ?>"
					   data-title="<?php echo $fileinfo->getFilename(); ?>">
                        <img title="<?=$fileinfo->getFilename() ?>" src="<?=$imageUrl; ?>"
							 width="80%"></a>
				</div>
				<?php } else { ?>
					<h1><a href="folder.php?f=<?=$fileinfo->getFilename() ?>"><?=$fileinfo->getFilename() ?></a></h1>
				<?php } ?>
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
