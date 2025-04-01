<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Character;
use App\Entity\ClassEnum;
use PHPUnit\Framework\TestCase;
class CharacterTest extends TestCase
{
    public function testCharacterAttributes(): void
    {

        $character = new Character('Anne', 9, 10, ClassEnum::WARRIOR, 1000);

        $this->assertEquals('Anne', $character->getName());
        $this->assertEquals(9, $character->getStrength());
        $this->assertSame(ClassEnum::WARRIOR, $character->getClass());
        $this->assertEquals(30, $character->getHealth());
        $this->assertEquals(10, $character->getConstitution());
        $this->assertEquals(1000, $character->getXp());
        $this->assertEquals(6, $character->getDefense());
        $this->assertEquals(11, $character->getAttack());

        $character->setStrength(10);
        $this->assertEquals(10, $character->getStrength());

        $character->setConstitution(10);
        $this->assertEquals(10, $character->getConstitution());

        $character->setHealth(10);
        $this->assertEquals(10, $character->getHealth());
    }
    public function testCharacterSetterAttributes(): void
    {
        $character = new Character('Hero', 10, 10, ClassEnum::WARRIOR, 1000, 1);

        $character->setName('Hero 2');
        $this->assertEquals('Hero 2', $character->getName());

        $character->setStrength(15);
        $this->assertEquals(15, $character->getStrength());

        $character->setConstitution(15);
        $this->assertEquals(15, $character->getConstitution());

        $character->setHealth(15);
        $this->assertEquals(15, $character->getHealth());

        $character->addXp(15);
        $this->assertEquals(1015, $character->getXp());

        $character->setClass(ClassEnum::MAGE);
        $this->assertEquals(ClassEnum::MAGE, $character->getClass());

        $character->setLevel(5);
        $this->assertEquals(5, $character->getLevel());
    }

    public function testHealthLimit(): void
    {
        $character = new Character('Hero', 10, 10, ClassEnum::WARRIOR, 1000, 1);

        $character->setHealth(-33);
        $this->assertEquals(0, $character->getHealth());
    }
}