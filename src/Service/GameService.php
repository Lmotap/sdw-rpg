<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Ennemy;
use App\Entity\EnnemiesName;
use Random\Randomizer;
use Symfony\Component\Console\Output\OutputInterface;

class GameService
{
    public function getPlayerCharacter(): Character
    {
        return new Character('Hero', rand(1, 10), rand(1, 10), 'Warrior');
    }

    public function generateEnemy(): Ennemy
    {
        // TODO : Use Randomizer for better readability for ENUM
        return new Ennemy(EnnemiesName::cases()[rand(0, count(EnnemiesName::cases()) - 1)], rand(1, 5), rand(1, 5));
    }
    public function fight(Character $character, Ennemy $ennemy, ?OutputInterface $output = null): string
    {
        $round = 0;
        while($character->getHealth() > 0 && $ennemy->getHealth() > 0) {
            $round++;
            $output?->writeln('HP du personnage: ' . $character->getHealth());
            $output?->writeln('HP de l\'ennemi: ' . $ennemy->getHealth());

            $damage =  $this->hitRoll($character, $ennemy);
            $ennemy->setHealth($ennemy->getHealth() - $damage);
            if ($ennemy->getHealth() <= 0) {
                return 'You win';
            }

            $damage =  $this->enemyHitRoll($character, $ennemy);
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

    public function hitRoll(Character $character, Ennemy $ennemy): int
    {
        $hitPercentage = 75 + ($character->getStrength() - $ennemy->getConstitution()) * 3 + $character->getLevel();

        $roll = rand(1, 100);

        if ($roll < $hitPercentage) {
            // Damage = max⁡((Attacker Attack − Defender Defense) × R, 1)
            $damage = max(($character->getAttack() - $ennemy->getDefense()) * 0.8 - 1.2,1);
            return $damage;
        } else {
            return 0;
        }
    }

    public function enemyHitRoll(Character $character, Ennemy $ennemy): int
    {
        $hitPercentage = 75 + ($character->getStrength() - $ennemy->getConstitution()) * 3 + $character->getLevel();

        $roll = rand(1, 100);


        if ($roll < $hitPercentage) {
            // Damage = max⁡((Attacker Attack − Defender Defense) × R, 1)
            $damage = max(($ennemy->getAttack() - $character->getDefense()) * 0.8 - 1.2,1);
            $character->setHealth($character->getHealth() - $damage);
            return $damage;
        } else {
            return 0;
        }
    }
}