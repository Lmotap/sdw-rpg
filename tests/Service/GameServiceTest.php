<?php

namespace App\Tests\Service;

use App\Entity\Character;
use App\Entity\ClassEnum;
use App\Entity\EnemiesNameEnum;
use App\Entity\Enemy;
use App\Service\GameService;
use App\Service\Randomizer;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class GameServiceTest extends TestCase
{
    private EntityManagerInterface $em;
    private Randomizer $randomizer;
    private GameService $gameService;

    protected function setUp(): void
    {
        // On n'a pas besoin d'une vraie base de données pour les tests
        $this->em = $this->createMock(EntityManagerInterface::class);
        
        // On mock le Randomizer pour avoir des résultats prévisibles
        // Cela nous permet de tester des scénarios spécifiques
        $this->randomizer = $this->createMock(Randomizer::class);
        
        $this->gameService = new GameService($this->em, $this->randomizer);
    }

    public function testHitRollSuccess()
    {
        $character = new Character('toto', 3, 3, ClassEnum::WARRIOR);
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);

        // hitPercentage = 75 + (3-1)*3 + 1 = 82
        // On utilise 50 comme roll pour être sûr d'être en dessous du hitPercentage
        $this->randomizer->method('rand')->willReturn(50);
        $result = $this->gameService->hitRoll($character, $enemy);
        $this->assertEquals(2, $result);
    }

    public function testHitRollMiss()
    {
        $character = new Character('toto', 3, 3, ClassEnum::WARRIOR);
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);

        $this->randomizer->method('rand')->willReturn(90);
        $result = $this->gameService->hitRoll($character, $enemy);
        $this->assertEquals(0, $result);
    }

    public function testEnemyHitRollSuccess()
    {
        $character = new Character('toto', 3, 3, ClassEnum::WARRIOR);
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);

        $this->randomizer->method('rand')->willReturn(50);
        $result = $this->gameService->enemyHitRoll($character, $enemy);
        
        // Damage = max((Attacker Attack - Defender Defense) * 0.8 - 1.2, 1)
        // Attack = 1 + 1 = 2
        // Defense = 3 + 1 = 4
        // Damage = max((2 - 4) * 0.8 - 1.2, 1) = max(-1.6 - 1.2, 1) = max(-2.8, 1) = 1
        $this->assertEquals(1, $result);
    }

    public function testEnemyHitRollMiss()
    {
        $character = new Character('toto', 3, 3, ClassEnum::WARRIOR);
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);

        $this->randomizer->method('rand')->willReturn(80);
        $result = $this->gameService->enemyHitRoll($character, $enemy);
        $this->assertEquals(0, $result);
    }

    public function testGetPlayerCharacter()
    {
        $characterRepository = $this->createMock(\Doctrine\ORM\EntityRepository::class);

        $characterRepository->method('findOneBy')
            ->willReturn(null);

        $this->em->method('getRepository')
            ->with(Character::class)
            ->willReturn($characterRepository);

        $this->em->expects($this->exactly(2))
            ->method('persist');

        $this->em->expects($this->exactly(2))
            ->method('flush');

        $character = $this->gameService->getPlayerCharacter();

        $this->assertEquals('Hero', $character->getName());
        $this->assertEquals(10, $character->getStrength());
        $this->assertEquals(10, $character->getConstitution());
        $this->assertEquals(ClassEnum::WARRIOR, $character->getClass());
        $this->assertEquals(30, $character->getHealth());
    }

    public function testGenerateEnemy()
    {
        $characterRepository = $this->createMock(\Doctrine\ORM\EntityRepository::class);
        $character = new Character('Hero', 10, 10, ClassEnum::WARRIOR);
        $character->setLevel(1);

        $characterRepository->method('findOneBy')
            ->willReturn($character);

        $this->em->method('getRepository')
            ->with(Character::class)
            ->willReturn($characterRepository);

        $this->randomizer->expects($this->exactly(4))
            ->method('rand')
            ->willReturnMap([
                [0, count(EnemiesNameEnum::cases()) - 1, 0], // Pour le type d'ennemi
                [-1, 2, 0],  // Pour le niveau (niveau_joueur + 0)
                [1, 3, 1],   // Pour la force (1 + niveau * 0.7)
                [1, 3, 1]    // Pour la constitution (1 + niveau * 0.5)
            ]);

        $enemy = $this->gameService->generateEnemy();

        $this->assertInstanceOf(Enemy::class, $enemy);
        $this->assertEquals(EnemiesNameEnum::cases()[0], $enemy->getName());
        $this->assertEquals(1, $enemy->getLevel());
        $this->assertEquals(1, $enemy->getStrength());
        $this->assertEquals(1, $enemy->getConstitution());
    }

    public function testFight()
    {
        $output = $this->createMock(OutputInterface::class);

        $character = new Character('toto', 3, 3, ClassEnum::WARRIOR);
        $character->setHealth(1000);
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);
        $enemy->setHealth(5);

        // On simule des coups réussis pour le personnage
        $this->randomizer->method('rand')->willReturn(50);

        $result = $this->gameService->fight($character, $enemy, $output);
        $this->assertEquals('You win', $result);

        $character->setHealth(1);
        $enemy->setHealth(1000);
        $result = $this->gameService->fight($character, $enemy, $output);
        $this->assertEquals('You lose', $result);

        $character->setHealth(1000);
        $enemy->setHealth(1000);
        $result = $this->gameService->fight($character, $enemy, $output);
        $this->assertEquals('This game is a draw', $result);
    }
}
