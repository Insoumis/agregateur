<?php

if (file_exists('config/prod.php')) {
    include_once 'config/prod.php';
} else if (file_exists('config/dev.php')) {
    include_once 'config/dev.php';
} else {
  trigger_error('Impossible de charger la configuration (config/dev.php ou config/prod.php requis).', E_USER_ERROR);
}

require_once 'config/tables.php';
require_once 'config/defines.php';

/**
 * Pas le temps d'inclure un autoload ;)
 */
require_once 'class/libs/MyPDO.php';
require_once 'class/libs/Base.php';


require_once 'class/app/Source.php';
require_once 'class/app/Content.php';

require_once 'modules/cron/update.php';

