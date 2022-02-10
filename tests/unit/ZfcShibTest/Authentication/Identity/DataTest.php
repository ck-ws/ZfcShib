<?php

namespace ZfcShibTest\Authentication\Identity;

use ZfcShib\Authentication\Identity\Data;


class DataTest extends \PHPUnit\Framework\TestCase
{


    public function testConstructor()
    {
        $userData = array(
            'foo1' => 'bar1'
        );
        $systemData = array(
            'foo2' => 'bar2'
        );
        
        $identityData = new Data($userData, $systemData);
        
        $this->assertSame($userData, $identityData->getUserData());
        $this->assertSame($systemData, $identityData->getSystemData());
    }
}
