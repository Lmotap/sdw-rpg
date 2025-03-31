<?php

namespace App\Command;

use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ClassEnum;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:character:reset',
    description: 'Reset or create a new player character',
)]
class CharacterResetCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $characters = $this->entityManager->getRepository(Character::class)->findAll();
        $output->writeln("Trouvé " . count($characters) . " personnage(s) dans la base de données.");
        
        foreach ($characters as $character) {
            $this->entityManager->remove($character);
        }
        $this->entityManager->flush();
        $output->writeln("Tous les personnages ont été supprimés.");
        
        $newCharacter = new Character('Hero', 10, 10, ClassEnum::WARRIOR);
        $this->entityManager->persist($newCharacter);
        $this->entityManager->flush();
        
        $output->writeln("Nouveau personnage créé:");
        $output->writeln("Nom: " . $newCharacter->getName());
        $output->writeln("Classe: " . $newCharacter->getClass()->value);
        $output->writeln("HP: " . $newCharacter->getHealth());
        $output->writeln("Force: " . $newCharacter->getStrength());
        $output->writeln("Constitution: " . $newCharacter->getConstitution());
        $output->writeln("Niveau: " . $newCharacter->getLevel());
        $output->writeln("XP: " . $newCharacter->getXp());
        
        return Command::SUCCESS;
    }
}