<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Ennemy;
use App\Entity\EnnemiesName;
use PHPUnit\Framework\TestCase;

class EnnemyTest extends TestCase
{
    public function testEnnemyAttributes(): void
    {

        $ennemy = new Ennemy(EnnemiesName::GOBLIN, 10, 10);

        $this->assertEquals(EnnemiesName::GOBLIN, $ennemy->getName());
        $this->assertEquals(10, $ennemy->getStrength());
        $this->assertEquals(10, $ennemy->getConstitution());
        $this->assertNotSame($ennemy, $ennemy->getStrength());
    }

    public function testEnnemyHp(): void
    {
        $ennemy = new Ennemy(EnnemiesName::ORC, 9, 10);
        $this->assertEquals(30, $ennemy->getHealth());
    }

    public function testEnnemyAttack(): void
    {
        $ennemy = new Ennemy(EnnemiesName::GOBLIN, 9, 10);
        $this->assertEquals(11, $ennemy->getAttack());
    }

    public function testEnnemyDefense(): void
    {
        $ennemy = new Ennemy(EnnemiesName::GOBLIN, 9, 10);
        $this->assertEquals(6, $ennemy->getDefense());
    }

}