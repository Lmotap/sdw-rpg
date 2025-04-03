<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Enemy;
use App\Entity\EnemiesNameEnum;
use PHPUnit\Framework\TestCase;

class EnemyTest extends TestCase
{
    public function testEnemyAttributes(): void
    {

        $enemy = new Enemy(EnemiesNameEnum::GOBLIN, 10, 10);

        $this->assertEquals(EnemiesNameEnum::GOBLIN, $enemy->getName());
        $this->assertEquals(10, $enemy->getStrength());
        $this->assertEquals(10, $enemy->getConstitution());
        $this->assertNotSame($enemy, $enemy->getStrength());
    }

    public function testEnemyHp(): void
    {
        $enemy = new Enemy(EnemiesNameEnum::ORC, 9, 10);
        $this->assertEquals(30, $enemy->getHealth());
    }

    public function testEnemyAttack(): void
    {
        $enemy = new Enemy(EnemiesNameEnum::GOBLIN, 9, 10);
        // Attack = 5 + (strength * 1.5) + (level * 2)
        // = 5 + (9 * 1.5) + (0 * 2)
        // = 5 + 13.5 + 0
        // = 18
        $this->assertEquals(18, $enemy->getAttack());
    }

    public function testEnemyDefense(): void
    {
        $enemy = new Enemy(EnemiesNameEnum::GOBLIN, 9, 10);
        // Defense = 1 + (constitution * 0.3) + (level * 0.5)
        // = 1 + (10 * 0.3) + (0 * 0.5)
        // = 1 + 3 + 0
        // = 4
        $this->assertEquals(4, $enemy->getDefense());
    }

    public function testEnemySetterAttributes(): void
    {
        $enemy = new Enemy(EnemiesNameEnum::GOBLIN, 10, 10);

        $enemy->setName(EnemiesNameEnum::ORC);
        $this->assertEquals(EnemiesNameEnum::ORC, $enemy->getName());

        $enemy->setStrength(15);
        $this->assertEquals(15, $enemy->getStrength());

        $enemy->setConstitution(15);
        $this->assertEquals(15, $enemy->getConstitution());

        $enemy->setHealth(15);
        $this->assertEquals(15, $enemy->getHealth());

        $enemy->setLevel(5);
        $this->assertEquals(5, $enemy->getLevel());
    }

    public function testGetNameAsString(): void
    {
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);
        $this->assertEquals('🐲 Dragon', $enemy->getNameAsString());
    }

    public function testGetRandomEnemy(): void
    {
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);
        $randomIndex = $enemy->getRandomEnemy();
        
        $this->assertGreaterThanOrEqual(0, $randomIndex);
        $this->assertLessThan(count(EnemiesNameEnum::cases()), $randomIndex);
    }

}