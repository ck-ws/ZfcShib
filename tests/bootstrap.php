<?php

declare(strict_types=1);

use Laminas\Loader\AutoloaderFactory;
use ZfcShib\Module;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Module.php';

$module = new Module();
AutoloaderFactory::factory($module->getAutoloaderConfig());
