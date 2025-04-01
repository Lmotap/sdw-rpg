<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Enemy;
use App\Entity\EnemiesNameEnum;
use App\Entity\ClassEnum;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Randomizer $randomizer,
    )
    {
    }

    public function getPlayerCharacter(): Character
    {
        $character = $this->entityManager->getRepository(Character::class)->findOneBy([]);
        
        if ($character === null) {
            $character = new Character('Hero', 10, 10, ClassEnum::WARRIOR);
            $this->entityManager->persist($character);
            $this->entityManager->flush();
        }
        
        $maxHealth = 10 + ($character->getConstitution() * 2) + ($character->getLevel() * 2);
        $character->setHealth($maxHealth);
        $this->entityManager->persist($character);
        $this->entityManager->flush();
        
        return $character;
    }

    public function generateEnemy(): Enemy
    {
        $character = $this->getPlayerCharacter();
        $playerLevel = $character->getLevel();
        
        $randomEnemyType = EnemiesNameEnum::cases()[$this->randomizer->rand(0, count(EnemiesNameEnum::cases()) - 1)];
        
        $enemyLevel = max(1, $playerLevel + $this->randomizer->rand(-1, 2));
        
        $strength = $this->randomizer->rand(1, 3) + floor($enemyLevel * 0.7);
        $constitution = $this->randomizer->rand(1, 3) + floor($enemyLevel * 0.5);
        
        return new Enemy($randomEnemyType, $strength, $constitution, 0, $enemyLevel);
    }
    
    public function fight(Character $character, Enemy $enemy, ?OutputInterface $output = null): string
    {
        $round = 0;
        while($character->getHealth() > 0 && $enemy->getHealth() > 0) {
            $round++;
            $output?->writeln('HP du personnage: ' . $character->getHealth());
            $output?->writeln('HP de l\'ennemi: ' . $enemy->getHealth());

            $damage =  $this->hitRoll($character, $enemy);
            $enemy->setHealth($enemy->getHealth() - $damage);
            if ($enemy->getHealth() <= 0) {
                return 'You win';
            }

            $damage =  $this->enemyHitRoll($character, $enemy);
            $character->setHealth($character->getHealth() - $damage);

            if ($character->getHealth() <= 0) {
                return 'You lose';
            }

            if ($round === 50) {
                return 'This game is a draw';
            }
        }
        return 'winner?';
    }

    public function hitRoll(Character $character, Enemy $enemy): int
    {
        $roll = $this->randomizer->rand(1, 100);

        $hitPercentage = 75 + ($character->getStrength() - $enemy->getConstitution()) * 3 + $character->getLevel();

        if ($roll < $hitPercentage) {
            // Damage = max⁡((Attacker Attack − Defender Defense) × R, 1)
            return max(($character->getAttack() - $enemy->getDefense()) * 0.8 - 1.2, 1);
        } else {
            return 0;
        }
    }

    public function enemyHitRoll(Character $character, Enemy $enemy): int
    {
        $roll = $this->randomizer->rand(1, 100);

        $hitPercentage = 75 + ($enemy->getStrength() - $character->getConstitution()) * 3 + $enemy->getLevel();

        if ($roll < $hitPercentage) {
            // Damage = max⁡((Attacker Attack − Defender Defense) × R, 1)
            return max(($enemy->getAttack() - $character->getDefense()) * 0.8 - 1.2, 1);
        } else {
            return 0;
        }
    }
}