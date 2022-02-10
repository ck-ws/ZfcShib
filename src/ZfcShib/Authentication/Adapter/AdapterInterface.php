<?php

declare(strict_types=1);

namespace ZfcShib\Authentication\Adapter;

use Laminas\Authentication\Adapter\AdapterInterface as ZendAuthAdapterInterface;
use ZfcShib\Authentication\Identity\IdentityFactoryInterface;

/**
 * The interface extends the standard Zend authentication adapter interface.
 */
interface AdapterInterface extends ZendAuthAdapterInterface
{
    /**
     * Sets a custom identity factory.
     */
    public function setIdentityFactory(IdentityFactoryInterface $identityFactory);
}
