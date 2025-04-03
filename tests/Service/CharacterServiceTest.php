<?php

namespace App\Tests\Service;

use App\Entity\Character;
use App\Entity\ClassEnum;
use App\Entity\EnemiesNameEnum;
use App\Entity\Enemy;
use App\Service\CharacterService;
use App\Service\Randomizer;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;

class CharacterServiceTest extends TestCase
{
    private EntityManager $em;
    private Randomizer $randomizer;
    private CharacterService $characterService;
    private QuestionHelper $questionHelper;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->em
            ->method('persist');
        $this->em
            ->method('flush');

        $this->randomizer = $this->createMock(Randomizer::class);
        $this->questionHelper = $this->createMock(QuestionHelper::class);
        $this->characterService = new CharacterService($this->randomizer, $this->questionHelper);
    }

    public function testCalculateXp()
    {
        $this->randomizer->expects($this->once())
            ->method('rand')
            ->with(10, 30)
            ->willReturn(17);

        $character = new Character('Hero', 3, 3, ClassEnum::WARRIOR);
        $enemy = new Enemy(EnemiesNameEnum::DEMON, 1, 1, 1);

        $character->setXp(0);
        $enemy->setHealth(0);
        $result = $this->characterService->calculateXp(1, 0, 10, $enemy, $character, $this->em);
        
        $this->assertEquals(37, $result);

        $character->setXp(300);
        $enemy->setHealth(10);
        $result = $this->characterService->calculateXp(1, 300, 0, $enemy, $character, $this->em);
        
        $this->assertEquals(201, $result);
    }

    public function testLevelUpConstitution()
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $this->questionHelper->expects($this->once())
            ->method('ask')
            ->willReturn('Constitution');

        $output->expects($this->once())
            ->method('writeln')
            ->with('🛡️ Votre constitution a augmenté de 1!');

        $character = new Character('toto', 3, 3, ClassEnum::WARRIOR);
        $character->setLevel(1);
        $character->setXp(100); 

        $result = $this->characterService->levelUp($character, $input, $output, $this->em);
        
        $this->assertEquals(2, $result);
        $this->assertEquals(0, $character->getXp());
        $this->assertEquals(4, $character->getConstitution()); 
    }

    public function testLevelUpStrength()
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $this->questionHelper->expects($this->once())
            ->method('ask')
            ->willReturn('Strength');

        $output->expects($this->once())
            ->method('writeln')
            ->with('💪 Votre force a augmenté de 1!');

        $character = new Character('toto', 3, 3, ClassEnum::WARRIOR);
        $character->setLevel(1);
        $character->setXp(100);

        $result = $this->characterService->levelUp($character, $input, $output, $this->em);

        $this->assertEquals(2, $result);
        $this->assertEquals(0, $character->getXp());
        $this->assertEquals(4, $character->getStrength());
    }

    public function testGetRequiredXpForNextLevel()
    {
        $this->assertEquals(100, $this->characterService->getRequiredXpForNextLevel(1));
        $this->assertEquals(200, $this->characterService->getRequiredXpForNextLevel(2));
        $this->assertEquals(300, $this->characterService->getRequiredXpForNextLevel(3)); 
    }

    public function testCanLevelUp()
    {
        $character = new Character('Hero', 3, 3, ClassEnum::WARRIOR);

        $character->setLevel(1);
        $character->setXp(100);
        $this->assertTrue($this->characterService->canLevelUp($character));

        $character->setXp(50);
        $this->assertFalse($this->characterService->canLevelUp($character));

        $character->setLevel(2);
        $character->setXp(200);
        $this->assertTrue($this->characterService->canLevelUp($character));
    }

    public function testCalculateXpWithHighLevel()
    {
        $character = new Character('Hero', 3, 3, ClassEnum::WARRIOR);
        $character->setLevel(25);
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);
        
        $result = $this->characterService->calculateXp(25, 0, 100, $enemy, $character, $this->em);
        
        $this->assertEquals(20, $result);
    }

    public function testCalculateXpWithNormalLevel()
    {
        $character = new Character('Hero', 3, 3, ClassEnum::WARRIOR);
        $character->setLevel(5);
        $enemy = new Enemy(EnemiesNameEnum::DRAGON, 1, 1);
        
        $this->em->expects($this->once())
            ->method('persist')
            ->with($character);
            
        $this->em->expects($this->once())
            ->method('flush');
        
        $result = $this->characterService->calculateXp(5, 0, 100, $enemy, $character, $this->em);
        
        $this->assertEquals(5, $result);
    }
}
