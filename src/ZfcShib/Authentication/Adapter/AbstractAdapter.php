<?php

declare(strict_types=1);

namespace ZfcShib\Authentication\Adapter;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;
use ZfcShib\Authentication\Identity\ArrayFactory;
use ZfcShib\Authentication\Identity\Data;
use ZfcShib\Authentication\Identity\IdentityFactoryInterface;

use function is_array;

/**
 * Abstract adapter class to be used as a superclass, implementing some common features.
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * The adapter configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The server vars ($_SERVER).
     *
     * @var array
     */
    protected $serverVars = [];

    /**
     * Identity factory.
     *
     * @var IdentityFactoryInterface
     */
    protected $identityFactory;

    /**
     * @param array $config Adapter options.
     * @param array $serverVars If the array is set, it will be used instead of the standard $_SERVER array.
     * @param IdentityFactoryInterface $identityFactory Optional identity factory.
     */
    public function __construct(
        array $config = [],
        ?array $serverVars = null,
        ?IdentityFactoryInterface $identityFactory = null
    ) {
        $this->setConfig($config);

        if (null === $serverVars) {
            $serverVars = $_SERVER;
        }

        $this->setServerVars($serverVars);

        if (null !== $identityFactory) {
            $this->setIdentityFactory($identityFactory);
        }
    }

    /**
     * Sets the configuration.
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Returns the configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns a configuration field value.
     *
     * @param string $name
     * @return mixed|null
     */
    public function getConfigVar($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }

        return null;
    }

    /**
     * Sets the server variables.
     *
     * @param array $serverVars
     */
    public function setServerVars(array $serverVars)
    {
        $this->serverVars = $serverVars;
    }

    /**
     * Returns the server variables.
     *
     * @return array
     */
    public function getServerVars()
    {
        return $this->serverVars;
    }

    /**
     * Returns a server variable value.
     *
     * @param string $name
     * @return array|null
     */
    public function getServerVar($name)
    {
        if (isset($this->serverVars[$name])) {
            return $this->serverVars[$name];
        }

        return null;
    }

    /**
     * Sets the identity factory.
     */
    public function setIdentityFactory(IdentityFactoryInterface $identityFactory)
    {
        $this->identityFactory = $identityFactory;
    }

    /**
     * Returns the identity factory.
     *
     * @return IdentityFactoryInterface
     */
    public function getIdentityFactory()
    {
        if (null === $this->identityFactory) {
            $this->identityFactory = new ArrayFactory();
        }

        return $this->identityFactory;
    }

    /**
     * Creates and returns the user identity based on the provided user data.
     *
     * @param array $identityData
     * @return mixed
     */
    public function createIdentity(Data $identityData)
    {
        return $this->getIdentityFactory()->createIdentity($identityData);
    }

    /**
     * Creates a success response object with the user identity contained in the provided data.
     *
     * @param array $userData
     * @param array $systemData
     * @return Result
     */
    protected function createSuccessfulAuthenticationResult(array $userData, array $systemData = [])
    {
        $identityData = $this->createIdentityData($userData, $systemData);
        return $this->createAuthenticationResult(Result::SUCCESS, $this->createIdentity($identityData));
    }

    /**
     * Creates a failure authentication response object.
     *
     * @param integer $code
     * @param array|string $messages
     * @return Result
     */
    protected function createFailureAuthenticationResult($code, $messages)
    {
        if (! is_array($messages)) {
            $messages = [
                $messages,
            ];
        }
        if ($code === null) {
            $code = Result::FAILURE;
        }
        return $this->createAuthenticationResult($code, null, $messages);
    }

    /**
     * Creates an authentication result object.
     *
     * @param integer $code
     * @param mixed $identity
     * @param array $messages
     * @return Result
     */
    protected function createAuthenticationResult($code = Result::FAILURE, $identity = null, array $messages = [])
    {
        return new Result($code, $identity, $messages);
    }

    /**
     * Creates an identity value object based on the user and system data.
     *
     * @param array $userData
     * @param array $systemData
     * @return Data
     */
    protected function createIdentityData(array $userData, array $systemData = [])
    {
        return new Data($userData, $systemData);
    }
}
