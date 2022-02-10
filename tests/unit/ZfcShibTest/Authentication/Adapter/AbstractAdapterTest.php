<?php

namespace ZfcShibTest\Authentication\Adapter;

use ZfcShib\Authentication\Adapter\AbstractAdapter;
use ZfcShib\Authentication\Identity\Data;
use ZfcShib\Authentication\Identity\IdentityFactoryInterface;

class AbstractAdapterTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var AbstractAdapter
     */
    protected $adapter = null;


    public function testConstructorWithNoArguments()
    {
        $adapter = $this->createAdapter();
        
        $this->assertSame(array(), $adapter->getConfig());
        $this->assertSame(array(), $adapter->getServerVars());
        $this->assertInstanceOf(IdentityFactoryInterface::class, $adapter->getIdentityFactory());
    }


    public function testConstructorWithArguments()
    {
        $config = array(
            'foo' => 'bar'
        );
        
        $serverVars = array(
            'var1' => 'value1'
        );
        
        $identityFactory = $this->getMockBuilder(IdentityFactoryInterface::class)->getMock();
        
        $adapter = $this->createAdapter($config, $serverVars, $identityFactory);
        
        $this->assertSame($config, $adapter->getConfig());
        $this->assertSame($serverVars, $adapter->getServerVars());
        $this->assertSame($identityFactory, $adapter->getIdentityFactory());
    }


    public function testSetConfig()
    {
        $config = array(
            'foo' => 'bar'
        );
        
        $adapter = $this->createAdapter();
        $adapter->setConfig($config);
        
        $this->assertSame($config, $adapter->getConfig());
    }


    public function testGetConfigVarWhenNonExistent()
    {
        $adapter = $this->createAdapter();
        $this->assertNull($adapter->getConfigVar('foo'));
    }


    public function testGetConfigVar()
    {
        $config = array(
            'foo' => 'bar'
        );
        
        $adapter = $this->createAdapter();
        $adapter->setConfig($config);
        
        $this->assertSame('bar', $adapter->getConfigVar('foo'));
    }


    public function testSetServerVars()
    {
        $serverVars = array(
            'var1' => 'value1'
        );
        
        $adapter = $this->createAdapter();
        $adapter->setServerVars($serverVars);
        
        $this->assertSame($serverVars, $adapter->getServerVars());
    }


    public function testGetServerVarWhenNonExistent()
    {
        $adapter = $this->createAdapter();
        $this->assertNull($adapter->getServerVar('foo'));
    }


    public function testGetServerVar()
    {
        $serverVars = array(
            'var1' => 'value1'
        );
        
        $adapter = $this->createAdapter();
        $adapter->setServerVars($serverVars);
        
        $this->assertSame('value1', $adapter->getServerVar('var1'));
    }


    public function testSetIdentityFactory()
    {
        $identityFactory = $this->getMockBuilder(IdentityFactoryInterface::class)->getMock();
        
        $adapter = $this->createAdapter();
        $adapter->setIdentityFactory($identityFactory);
        
        $this->assertSame($identityFactory, $adapter->getIdentityFactory());
    }


    public function testCreateIdentity()
    {
        $identityData = $this->getIdentityDataMock();
        $identity = array(
            'identity' => 'foo'
        );
        
        $identityFactory = $this->getMockBuilder(IdentityFactoryInterface::class)->getMock();
        $identityFactory->expects($this->once())
            ->method('createIdentity')
            ->with($identityData)
            ->will($this->returnValue($identity));
        
        $adapter = $this->createAdapter();
        $adapter->setIdentityFactory($identityFactory);
        
        $this->assertSame($identity, $adapter->createIdentity($identityData));
    }


    /**
     * @param array $config
     * @param array $serverVars
     * @param IdentityFactoryInterface $identityFactory
     * @return AbstractAdapter
     */
    protected function createAdapter(array $config = array(), array $serverVars = array(), IdentityFactoryInterface $identityFactory = null)
    {
        $adapter = $this->getMockForAbstractClass(AbstractAdapter::class, array(
            $config,
            $serverVars,
            $identityFactory
        ));
        
        return $adapter;
    }


    protected function getIdentityDataMock()
    {
        $data = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $data;
    }
}
