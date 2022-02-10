<?php

declare(strict_types=1);

namespace ZfcShib\Authentication\Adapter\Exception;

use RuntimeException;

use function sprintf;

class MissingConfigurationException extends RuntimeException
{
    public function __construct($configName)
    {
        parent::__construct(sprintf("Missing configuration directive '%s'", $configName));
    }
}
