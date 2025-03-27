<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Ennemy;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Output\OutputInterface;
class CharacterService
{

    public function calculateXp(int $lvl, int $xp, int $health, Ennemy $ennemy, Character $character, EntityManagerInterface $em): int
    {

        $xpByDefault = $character->getXp();

        if ($ennemy->getHealth() < 0) {
            $xpGained = ($ennemy->getLevel() * 20) + rand(10, 30);
            $character->addXp($xpGained);
            return $character->getXp();
        }

        if ($character->getXp() < 0) {
            $character->addXp(0);
        }

        $lvl = floor($character->getXp() / 100);

        if ($lvl > 20) {
            return 20;
        }

        if ($health < 0) {
            $xpAfterDeath = $xpByDefault * (1 - 0.33);
            return $xpAfterDeath;
        }

        $em->persist($character);
        $em->flush();

        return floor($lvl);  
    }

    public function levelUp(Character $character, OutputInterface $output, EntityManagerInterface $em): int
    {
        $character->setHealth($character->getHealth() + 10);
        dump($character->getHealth());
        $output->writeln('💪 You have gained 10 health points');

        switch ($character->getLevel() + 1) {
            case 1:
                $character->setStrength($character->getStrength() + 1);
                break;
            case 2:
                $character->setConstitution($character->getConstitution() + 1);
                break;
        }
        $em->persist($character);
        $em->flush();
        return $character->getLevel();
    }
}