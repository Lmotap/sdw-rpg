<?php

namespace App\Command;

use App\Service\GameService;
use App\Service\CharacterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:combat',
    description: 'Add a short description for your command',
)]
class CombatCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private GameService $gameService,
        private CharacterService $characterService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $character = $this->gameService->getPlayerCharacter();
        $enemy = $this->gameService->generateEnemy();

        $output->write('HP du personnage: ' . $character->getHealth() . ' ' . 'HP de l\'ennemi: ' . $enemy->getHealth());
        $output->writeln('');
        $output->writeln("⚔️ Combat starts: {$character->getName()} vs {$enemy->getNameAsString()}");

        $winner = $this->gameService->fight($character, $enemy, $output);

        $this->characterService->calculateXp($character->getLevel(), $character->getXp(), $character->getHealth(), $enemy, $character, $this->entityManager);

        
        $output->writeln("🏆 Winner: $winner");
        return Command::SUCCESS;
    }
}
