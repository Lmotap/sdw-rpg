<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Character;
use App\Entity\Ennemy;
use App\Entity\EnnemiesName;
use App\Service\CharacterService;
use PHPUnit\Framework\TestCase;

class CharacterTest extends TestCase
{
    public function testCharacterAttributes(): void
    {

        $character = new Character('Anne', 9, 10, 'Warrior');

        $this->assertEquals('Anne', $character->getName());
        $this->assertEquals(9, $character->getStrength());
        $this->assertSame('Warrior', $character->getClass());
        $this->assertEquals(30, $character->getHealth());
        $this->assertEquals(10, $character->getConstitution());
        $this->assertNotSame($character, $character->getStrength());
        $this->assertEquals(6, $character->getDefense());
        $this->assertEquals(11, $character->getAttack());

        $character->setStrength(10);
        $this->assertEquals(10, $character->getStrength());

        $character->setConstitution(10);
        $this->assertEquals(10, $character->getConstitution());

        $character->setHealth(10);
        $this->assertEquals(10, $character->getHealth());
    }

    public function testCharacterLevelUp(): void
    {
        $characterService = new CharacterService();

        $character = new Character('Anne', 10, 10, 'Warrior', 1000, 1, 20);
        $characterService->calculateXp(1, 1000, 20, new Ennemy(EnnemiesName::GOBLIN, 1, 1));
        $this->assertEquals(2, $character->getLevel());
    }
}