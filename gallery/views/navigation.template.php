<nav class="navbar navbar-default navbar-fixed-top"  role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <?php
      $folders = explode('/', $getFolder);
      if (count($folders) === 1) {  // Current path is not in a subdirectory, go back to root
        ?>
       <a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-home"></span> Main</a>
      <?php
      } else {
        $path = "";
        foreach ($folders as $folder) {
          $path .= $folder;
      ?>
          <a class="navbar-brand" href="../folder.php?f=<?=$path ?>"><span class="glyphicon glyphicon-folder-open"></span> <?=$folder ?></a>
      <?php
          $path .= '/';
        }
      }
      ?>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <a href="#" class="navbar-toggle" data-toggle="dropdown" data-target="#credits">Credits<span class="caret"></span></a>
    <div class="collapse navbar-collapse text-right" id="credits">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <ul class="dropdown-menu" role="menu">
            <li><a href="https://fasani.de">Designed by fasani.de</a></li>
            <li class="divider"></li>
            <li><a href="https://github.com/martinberlin/php-gallery">Get the source</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
