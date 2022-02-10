<?php

namespace ZfcShibTest\Authentication\Identity;

use ZfcShib\Authentication\Identity\ArrayFactory;


class ArrayFactoryTest extends \PHPUnit\Framework\TestCase
{


    public function testCreateIdentity()
    {
        $userData = array(
            'username' => 'foo'
        );
        $systemData = array(
            'session' => 'bar'
        );
        
        $identityData = $this->getMockBuilder(\ZfcShib\Authentication\Identity\Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $identityData->expects($this->once())
            ->method('getUserData')
            ->will($this->returnValue($userData));
        $identityData->expects($this->once())
            ->method('getSystemData')
            ->will($this->returnValue($systemData));
        
        $factory = new ArrayFactory();
        $identity = $factory->createIdentity($identityData);
        
        $this->assertSame($userData, $identity['user']);
        $this->assertSame($systemData, $identity['system']);
    }
}
