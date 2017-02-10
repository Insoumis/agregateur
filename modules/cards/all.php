<?php
$array_contents = Content::getAll();
$i = 0;

include_once 'templates/head.php';
?>
<div class="content websites">
    <h2>Dernières nouvelles</h2>
    <p class="description">Retrouvez en un seul endroit toutes les actualités du mouvement de la France Insoumise.</p>
    <div class="list">
      <?php

      foreach ($array_contents as $Content) {
          $i++;
          if ($i > 10) {
              break;
          }
          ?>
          <a class="embedly-card" href="<?php echo $Content->getUrl() ?>">
              <?php echo $Content->getTitle() ?>
          </a>
          <br />
          <?php
      }
      ?>

        <hr />
    </div>
    <div class="end">
        <p><a href="http://insoumis.online" title="Découvrir plus de sites">Retour sur le site des insoumis.</a></p>
    </div>
</div>
