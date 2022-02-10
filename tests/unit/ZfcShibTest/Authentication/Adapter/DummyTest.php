<?php

declare(strict_types=1);

namespace ZfcShibTest\Authentication\Adapter;

use Laminas\Authentication\Result;
use PHPUnit\Framework\TestCase;
use ZfcShib\Authentication\Adapter\Dummy;
use ZfcShib\Authentication\Adapter\Exception\MissingConfigurationException;

class DummyTest extends TestCase
{
    public function testAuthenticateWithNoConfig()
    {
        $this->expectException(MissingConfigurationException::class);

        $adapter = new Dummy();
        $adapter->authenticate();
    }

    public function testAuthenticate()
    {
        $config = [
            Dummy::CONFIG_USER_DATA   => [
                'username' => 'foo',
            ],
            Dummy::CONFIG_SYSTEM_DATA => [
                'session' => 'bar',
            ],
        ];

        $identity = [
            'id' => 123,
        ];

        $result = $this->getMockBuilder(Result::class)
            ->disableOriginalConstructor()
            ->getMock();
        $result->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $result->expects($this->once())
            ->method('getIdentity')
            ->will($this->returnValue($identity));

        $adapter = $this->getMockBuilder(Dummy::class)
            ->setConstructorArgs([
                $config,
            ])
            ->setMethods([
                'createSuccessfulAuthenticationResult',
            ])
            ->getMock();

        $adapter->expects($this->once())
            ->method('createSuccessfulAuthenticationResult')
            ->with($config[Dummy::CONFIG_USER_DATA], $config[Dummy::CONFIG_SYSTEM_DATA])
            ->will($this->returnValue($result));

        $result = $adapter->authenticate();

        $this->assertTrue($result->isValid());
        $this->assertSame($identity, $result->getIdentity());
    }
}
