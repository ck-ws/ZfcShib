<?php

declare(strict_types=1);

namespace ZfcShibTest\Authentication\Identity;

use PHPUnit\Framework\TestCase;
use ZfcShib\Authentication\Identity\Data;

class DataTest extends TestCase
{
    public function testConstructor()
    {
        $userData   = [
            'foo1' => 'bar1',
        ];
        $systemData = [
            'foo2' => 'bar2',
        ];

        $identityData = new Data($userData, $systemData);

        $this->assertSame($userData, $identityData->getUserData());
        $this->assertSame($systemData, $identityData->getSystemData());
    }
}
