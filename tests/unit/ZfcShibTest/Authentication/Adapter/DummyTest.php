<?php

namespace ZfcShibTest\Authentication\Adapter;

use ZfcShib\Authentication\Adapter\Dummy;


class DummyTest extends \PHPUnit\Framework\TestCase
{


    public function testAuthenticateWithNoConfig()
    {
        $this->expectException(\ZfcShib\Authentication\Adapter\Exception\MissingConfigurationException::class);
        
        $adapter = new Dummy();
        $adapter->authenticate();
    }


    public function testAuthenticate()
    {
        $config = array(
            Dummy::CONFIG_USER_DATA => array(
                'username' => 'foo'
            ),
            Dummy::CONFIG_SYSTEM_DATA => array(
                'session' => 'bar'
            )
        );
        
        $identity = array(
            'id' => 123
        );
        
        $result = $this->getMockBuilder(\Laminas\Authentication\Result::class)
            ->disableOriginalConstructor()
            ->getMock();
        $result->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $result->expects($this->once())
            ->method('getIdentity')
            ->will($this->returnValue($identity));
        
        $adapter = $this->getMockBuilder(Dummy::class)
            ->setConstructorArgs(array(
            $config
        ))
            ->setMethods(array(
            'createSuccessfulAuthenticationResult'
        ))
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
