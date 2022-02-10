<?php

use ZfcShib\Module;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Module.php';

$module = new Module();
\Laminas\Loader\AutoloaderFactory::factory($module->getAutoloaderConfig());
