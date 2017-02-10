<?php
include 'config/load.php';

$array_contents = Content::getAll();

$i = 0;
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
<script async src="//cdn.embedly.com/widgets/platform.js" charset="UTF-8"></script>
