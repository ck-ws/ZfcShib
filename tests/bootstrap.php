<?php

use ZfcShib\Module;

require __DIR__ . '/../../../autoload.php';
require __DIR__ . '/../Module.php';

$module = new Module();
\Laminas\Loader\AutoloaderFactory::factory($module->getAutoloaderConfig());