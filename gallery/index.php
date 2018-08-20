<?php
require "config.php";
include 'head.template.php'; ?>
<body>
<main role='main'><div class='container-fluid'>
  <div class="row">

	<?php

	$row_counter = 1;

	$dir = new DirectoryIterator($basedir);


	foreach ($dir as $fileinfo) {

		if ($fileinfo->isDir() && !$fileinfo->isDot()) {

			$images = scandir($basedir.$fileinfo->getFilename());
			$imageUrl = $thumbBaseDir.$fileinfo->getFilename().'/'.$images[2];
			// First and second have . .. in array
			$row_counter++;
			?>
			<div class="col-md-<?php echo $col_md_x; ?>">
				<div class="picture_card">
					<h3><a href="folder.php?f=<?=$fileinfo->getFilename() ?>"><?=$fileinfo->getFilename() ?></a></h3>
					<a href="folder.php?f=<?=$fileinfo->getFilename() ?>"
					   data-title="<?php echo $fileinfo->getFilename(); ?>">
                        <img title="<?=$fileinfo->getFilename() ?>" src="<?=$imageUrl; ?>"
							 width="80%"></a>
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
