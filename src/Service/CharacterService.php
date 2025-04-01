<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Enemy;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Input\InputInterface;

class CharacterService
{
    public function __construct(
        private Randomizer $randomizer,
        private ?QuestionHelper $questionHelper = null,
    )
    {
    }



    public function calculateXp(int $lvl, int $xp, int $health, Enemy $enemy, Character $character, EntityManager $em): int
    {
        $xpByDefault = $character->getXp();

        if ($enemy->getHealth() <= 0) {
            $xpGained = ($enemy->getLevel() * 20) + $this->randomizer->rand(10, 30);
            $character->addXp($xpGained);
            $em->persist($character);
            $em->flush();
            return $character->getXp();
        }

        if ($health <= 0) {
            $lostXp = (int)($xpByDefault * 0.33);
            $newXp = $xpByDefault - $lostXp;
            
            if ($newXp < 0) {
                $newXp = 0;
            }
            
            $character->setXp($newXp);
            $em->persist($character);
            $em->flush();
            return $character->getXp();
        }

        if ($lvl > 20) {
            return 20;
        }

        $em->persist($character);
        $em->flush();

        return floor($lvl);  
    }

    public function levelUp(Character $character, InputInterface $input, OutputInterface $output, EntityManagerInterface $em): int
    {        
        $helper = $this->questionHelper ?? new QuestionHelper();
        $question = new ChoiceQuestion(
            'Choissisez une caractéristique à augmenter (0 pour Strength, 1 pour Constitution):',
            ['Strength', 'Constitution'],
        );
        
        $answer = $helper->ask($input, $output, $question);
        
        if ($answer === 'Strength') {
            $character->setStrength($character->getStrength() + 1);
            $output->writeln('💪 Votre force a augmenté de 1!');
        } else {
            $character->setConstitution($character->getConstitution() + 1);
            $output->writeln('🛡️ Votre constitution a augmenté de 1!');
        }
        
        $newLevel = $character->getLevel() + 1;
        $character->setLevel($newLevel);
        
        $requiredXpForCurrentLevel = $this->getRequiredXpForNextLevel($character->getLevel() - 1);
        $character->setXp($character->getXp() - $requiredXpForCurrentLevel);
        
        $em->persist($character);
        $em->flush();
        
        return $character->getLevel();
    }

    public function getRequiredXpForNextLevel(int $currentLevel): int
    {
        return 100 * $currentLevel;
    }

    public function canLevelUp(Character $character): bool
    {
        $currentLevel = $character->getLevel();
        $currentXp = $character->getXp();
        $requiredXp = $this->getRequiredXpForNextLevel($currentLevel);
        
        
        return $currentXp >= $requiredXp;
    }
}