<?php

declare(strict_types=1);

namespace ZfcShib\Authentication\Adapter;

use function is_array;

/**
 * A dummy adapter for testing purposes. It always returns the same user identity, created from the user data
 * set in the configuration under the 'user_data' field.
 */
class Dummy extends AbstractAdapter
{
    const CONFIG_USER_DATA = 'user_data';

    const CONFIG_SYSTEM_DATA = 'system_data';

    /**
     * {@inheritdoc}
     *
     * @see \Laminas\Authentication\Adapter\AdapterInterface::authenticate()
     */
    public function authenticate()
    {
        $userData = $this->getConfigVar(self::CONFIG_USER_DATA);
        if (null === $userData || ! is_array($userData)) {
            throw new Exception\MissingConfigurationException(self::CONFIG_USER_DATA);
        }

        $systemData = $this->getConfigVar(self::CONFIG_SYSTEM_DATA);
        if (! isset($systemData) || ! is_array($systemData)) {
            $systemData = [];
        }

        return $this->createSuccessfulAuthenticationResult($userData, $systemData);
    }
}
