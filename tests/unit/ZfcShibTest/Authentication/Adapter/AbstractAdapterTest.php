<?php

declare(strict_types=1);

namespace ZfcShibTest\Authentication\Adapter;

use PHPUnit\Framework\TestCase;
use ZfcShib\Authentication\Adapter\AbstractAdapter;
use ZfcShib\Authentication\Identity\Data;
use ZfcShib\Authentication\Identity\IdentityFactoryInterface;

class AbstractAdapterTest extends TestCase
{
    /** @var AbstractAdapter */
    protected $adapter;

    public function testConstructorWithNoArguments()
    {
        $adapter = $this->createAdapter();

        $this->assertSame([], $adapter->getConfig());
        $this->assertSame([], $adapter->getServerVars());
        $this->assertInstanceOf(IdentityFactoryInterface::class, $adapter->getIdentityFactory());
    }

    public function testConstructorWithArguments()
    {
        $config = [
            'foo' => 'bar',
        ];

        $serverVars = [
            'var1' => 'value1',
        ];

        $identityFactory = $this->getMockBuilder(IdentityFactoryInterface::class)->getMock();

        $adapter = $this->createAdapter($config, $serverVars, $identityFactory);

        $this->assertSame($config, $adapter->getConfig());
        $this->assertSame($serverVars, $adapter->getServerVars());
        $this->assertSame($identityFactory, $adapter->getIdentityFactory());
    }

    public function testSetConfig()
    {
        $config = [
            'foo' => 'bar',
        ];

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
        $config = [
            'foo' => 'bar',
        ];

        $adapter = $this->createAdapter();
        $adapter->setConfig($config);

        $this->assertSame('bar', $adapter->getConfigVar('foo'));
    }

    public function testSetServerVars()
    {
        $serverVars = [
            'var1' => 'value1',
        ];

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
        $serverVars = [
            'var1' => 'value1',
        ];

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
        $identity     = [
            'identity' => 'foo',
        ];

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
     * @return AbstractAdapter
     */
    protected function createAdapter(array $config = [], array $serverVars = [], ?IdentityFactoryInterface $identityFactory = null)
    {
        return $this->getMockForAbstractClass(AbstractAdapter::class, [
            $config,
            $serverVars,
            $identityFactory,
        ]);
    }

    protected function getIdentityDataMock()
    {
        return $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
