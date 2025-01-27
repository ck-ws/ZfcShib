<?php

declare(strict_types=1);

namespace ZfcShib\Authentication\Identity;

/**
 * Simply returns the user identity as an array.
 */
class ArrayFactory implements IdentityFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \ZfcShib\Authentication\Identity\IdentityFactoryInterface::createIdentity()
     */
    public function createIdentity(Data $identityData)
    {
        return [
            'user'   => $identityData->getUserData(),
            'system' => $identityData->getSystemData(),
        ];
    }
}
