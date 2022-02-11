<?php

namespace ZfcShib;

use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;


class Module implements AutoloaderProviderInterface
{


    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
}