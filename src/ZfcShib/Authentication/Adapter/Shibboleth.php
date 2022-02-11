<?php

declare(strict_types=1);

namespace ZfcShib\Authentication\Adapter;

use Laminas\Authentication\Result;

use function is_array;
use function sprintf;

/**
 * Shibboleth authentication adapter.
 *
 * Extracts user's attributes from the environment and checks for user identity. If a Shibboleth session exists
 * and the required user identity attribute is present, a success authentication result is created with
 * the user's identity information.
 */
class Shibboleth extends AbstractAdapter
{
    /*
     * Configuration options:
     */
    /**
     * The name of the attribute containing the user's identity.
     */
    public const OPT_ID_ATTR_NAME = 'id_attr_name';

    /**
     * A list of user attribute names to be extracted. If not set, all attributes will be extracted.
     */
    public const OPT_USER_ATTR_NAMES = 'user_attr_names';

    /**
     * A list of system attribute names to be extracted. If not set, all attributes will be extracted.
     */
    public const OPT_SYSTEM_ATTR_NAMES = 'system_attr_names';

    /**
     * Default user ID attribute name.
     *
     * @var string
     */
    protected $idAttributeName = 'eppn';

    /**
     * User attribute names to be extracted from the server environment.
     *
     * @var array
     */
    protected $userAttributeNames = [
        'eppn',
        'peristent-id',
        'affiliation',
        'entitlement',
        'cn',
        'sn',
        'givenName',
        'displayName',
        'mail',
        'telephoneNumber',
        'employeeNumber',
        'employeeType',
        'preferredLanguage',
        'o',
        'ou',
    ];

    /**
     * System attribute names to be extracted from the server environment.
     *
     * @var array
     */
    protected $systemAttributeNames = [
        'Shib-Application-ID',
        'Shib-Identity-Provider',
        'Shib-Authentication-Instant',
        'Shib-Authentication-Method',
        'Shib-AuthnContext-Class',
        'Shib-Session-Index',
    ];

    /**
     * {@inheritdoc}
     *
     * @see \Laminas\Authentication\Adapter\AdapterInterface::authenticate()
     */
    public function authenticate()
    {
        $userId = $this->getUserId();
        if (null === $userId) {
            return $this->createFailureAuthenticationResult(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                sprintf("User identity attribute '%s' not found", $this->getIdAttributeName())
            );
        }

        $userData   = $this->extractAttributeValues($this->getUserAttributeNames(), $this->getServerVars());
        $systemData = $this->extractAttributeValues($this->getSystemAttributeNames(), $this->getServerVars());

        return $this->createSuccessfulAuthenticationResult($userData, $systemData);
    }

    /**
     * Returns the user ID.
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->getServerVar($this->getIdAttributeName());
    }

    /**
     * Returns the name of the attribute holding the user's identity.
     *
     * @return string
     */
    public function getIdAttributeName()
    {
        $idAttributeName = $this->getConfigVar(self::OPT_ID_ATTR_NAME);
        if (null === $idAttributeName) {
            $idAttributeName = $this->idAttributeName;
        }

        return $idAttributeName;
    }

    /**
     * Extracts user attribute values.
     *
     * @return array
     */
    public function getUserAttributeValues()
    {
        return $this->extractAttributeValues($this->getUserAttributeNames(), $this->getServerVars());
    }

    /**
     * Extracts system attribute values.
     *
     * @return array
     */
    public function getSystemAttributeValues()
    {
        return $this->extractAttributeValues($this->getSystemAttributeNames(), $this->getServerVars());
    }

    /**
     * Returns the list of user attribute names to be extracted from the environment.
     *
     * @return array
     */
    public function getUserAttributeNames()
    {
        return $this->getAttributeNames(self::OPT_USER_ATTR_NAMES, $this->userAttributeNames);
    }

    /**
     * Returns the list of system attributes to be extracted from the environment.
     *
     * @return array
     */
    public function getSystemAttributeNames()
    {
        return $this->getAttributeNames(self::OPT_SYSTEM_ATTR_NAMES, $this->systemAttributeNames);
    }

    /**
     * Generic method, which returns a list of attribute names. If the corresponding config field is set
     * ($configVarName) with relevant data, returns those data. Otherwise returns the provided default values.
     *
     * @param string $configVarName
     * @param array $defaultValue
     * @return array
     */
    protected function getAttributeNames($configVarName, array $defaultValue)
    {
        $attributeNames = $this->getConfigVar($configVarName);
        if (null !== $attributeNames && is_array($attributeNames)) {
            return $attributeNames;
        }

        return $defaultValue;
    }

    /**
     * Extracts and returns array members from $allValues which has keys contained in the $names array.
     *
     * @param array $names
     * @param array $allValues
     * @return array
     */
    protected function extractAttributeValues(array $names, array $allValues)
    {
        $values = [];
        foreach ($names as $name) {
            if (isset($allValues[$name])) {
                $values[$name] = $allValues[$name];
            }
        }

        return $values;
    }
}
