<?php

declare(strict_types=1);

namespace ZfcShibTest\Authentication\Identity;

use PHPUnit\Framework\TestCase;
use ZfcShib\Authentication\Identity\ArrayFactory;
use ZfcShib\Authentication\Identity\Data;

class ArrayFactoryTest extends TestCase
{
    public function testCreateIdentity()
    {
        $userData   = [
            'username' => 'foo',
        ];
        $systemData = [
            'session' => 'bar',
        ];

        $identityData = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $identityData->expects($this->once())
            ->method('getUserData')
            ->will($this->returnValue($userData));
        $identityData->expects($this->once())
            ->method('getSystemData')
            ->will($this->returnValue($systemData));

        $factory  = new ArrayFactory();
        $identity = $factory->createIdentity($identityData);

        $this->assertSame($userData, $identity['user']);
        $this->assertSame($systemData, $identity['system']);
    }
}
