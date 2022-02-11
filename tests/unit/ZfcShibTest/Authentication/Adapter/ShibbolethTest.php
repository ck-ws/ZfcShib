<?php

declare(strict_types=1);

namespace ZfcShibTest\Authentication\Adapter;

use Laminas\Authentication\Result;
use PHPUnit\Framework\TestCase;
use ZfcShib\Authentication\Adapter\Shibboleth;

class ShibbolethTest extends TestCase
{
    public function testAuthenticateWithNoUserId()
    {
        $adapter = new Shibboleth();
        $result  = $adapter->authenticate();

        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->isValid());
        $this->assertSame(Result::FAILURE_IDENTITY_NOT_FOUND, $result->getCode());
    }

    public function testAuthenticate()
    {
        $adapter = new Shibboleth([
            Shibboleth::OPT_ID_ATTR_NAME      => 'id',
            Shibboleth::OPT_SYSTEM_ATTR_NAMES => [
                'systemAttr',
            ],
            Shibboleth::OPT_USER_ATTR_NAMES   => [
                'userAttr',
            ],
        ], [
            'id'         => 'testuser',
            'systemAttr' => 'systemValue',
            'userAttr'   => 'userValue',
            'foo'        => 'bar',
        ]);

        $result = $adapter->authenticate();

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isValid());
        $this->assertEquals([
            'system' => [
                'systemAttr' => 'systemValue',
            ],
            'user'   => [
                'userAttr' => 'userValue',
            ],
        ], $result->getIdentity());
    }

    public function testGetUserId()
    {
        $adapter = new Shibboleth([
            Shibboleth::OPT_ID_ATTR_NAME => 'id',
        ], [
            'id' => 'testuser',
        ]);

        $this->assertSame('testuser', $adapter->getUserId());
    }

    public function testGetIdAttributeName()
    {
        $adapter = new Shibboleth([
            Shibboleth::OPT_ID_ATTR_NAME => 'id',
        ]);

        $this->assertSame('id', $adapter->getIdAttributeName());
    }

    public function testGetUserAttributeNames()
    {
        $attrNames = [
            'foo',
            'bar',
        ];
        $adapter   = new Shibboleth([
            Shibboleth::OPT_USER_ATTR_NAMES => $attrNames,
        ]);

        $this->assertSame($attrNames, $adapter->getUserAttributeNames());
    }

    public function testGetSystemAttributeNames()
    {
        $attrNames = [
            'foo',
            'bar',
        ];
        $adapter   = new Shibboleth([
            Shibboleth::OPT_SYSTEM_ATTR_NAMES => $attrNames,
        ]);

        $this->assertSame($attrNames, $adapter->getSystemAttributeNames());
    }

    public function testGetUserAttributeValues()
    {
        $attrNames = [
            'attr1',
            'attr2',
        ];
        $values    = [
            'attr1' => 'value1',
            'attr3' => 'value3',
        ];

        $adapter = new Shibboleth([
            Shibboleth::OPT_USER_ATTR_NAMES => $attrNames,
        ], $values);

        $this->assertSame([
            'attr1' => 'value1',
        ], $adapter->getUserAttributeValues());
    }

    public function testGetSystemAttributeValues()
    {
        $attrNames = [
            'attr1',
            'attr2',
        ];
        $values    = [
            'attr1' => 'value1',
            'attr3' => 'value3',
        ];

        $adapter = new Shibboleth([
            Shibboleth::OPT_SYSTEM_ATTR_NAMES => $attrNames,
        ], $values);

        $this->assertSame([
            'attr1' => 'value1',
        ], $adapter->getSystemAttributeValues());
    }
}
