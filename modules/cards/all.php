<?php
$start = 0;
if (!empty($_GET['st'])) {
    $start = (int)($_GET['st'] - 1) * 10;
}

$source_id = null;
$source_link = null;

if (!empty($_GET['source'])) {
    $source_id = $_GET['source'];
    
    $Source = new Source($source_id);
    if (!$Source->isSql()) {
        exit('Source not found');
    }
    
    $source_link = '&source=' . $Source->getId();
}

$array_contents_all = Content::getAll(null, false, null, null, $source_id);
$array_contents = Content::getAll(null, false, $start, 10, $source_id);

$array_sources = Source::getAll();

$max = count($array_contents_all);

include_once 'templates/head.php';
?>
    <div class="content websites">
        <h2>Dernières nouvelles</h2>
        <p class="description">
            Retrouvez en un seul endroit toutes les actualités du mouvement de la France Insoumise.
        </p>
        <nav class="sources">
            <ul>
                <?php
                foreach ($array_sources as $SourceNav) {
                    $class = '';
                    
                    if (!empty($_GET['source']) && $_GET['source'] == $SourceNav->getId()) {
                        $class = 'active';
                    }
                    ?>
                    <li>
                        <a href="?source=<?php echo $SourceNav->getId() ?>" class="source <?php echo $class ?>">
                            <?php echo $SourceNav->getTitle() ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </nav>
        <div class="list">
            <?php
            
            foreach ($array_contents as $Content) {
                ?>
                <a class="embedly-card" href="<?php echo $Content->getUrl() ?>">
                    <?php echo $Content->getTitle() ?>
                </a>
                <br/>
                <?php
            }
            
            ?>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= ceil($max / 10); $i = $i + 1) {
                    $class = '';
                    
                    if (empty($_GET['st']) && $i == 1 || (!empty($_GET['st']) && $_GET['st'] == $i)) {
                        $class = 'active';
                    }
                    
                    ?>
                    <a href="?st=<?php echo $i . $source_link ?>" class="<?php echo $class ?>"><?php echo $i; ?></a>
                    <?php
                }
                ?>
            </div>
            <hr/>
        </div>
        <div class="end">
            <p><a href="http://insoumis.online" title="Découvrir plus de sites">Retour sur le site des insoumis.</a></p>
        </div>
    </div>
<?php
include_once 'templates/foot.php';
