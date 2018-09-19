<?php

require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Europe/Brussels');

use \Kanooh\WebDriver\BrowserConfig;

foreach (array('browsers.dist.yml', 'browsers.yml') as $browsers_config_file) {
    if (file_exists($browsers_config_file)) {
        BrowserConfig::read($browsers_config_file);
    }
}
