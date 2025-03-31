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
        private GameService $gameService,
        private CharacterService $characterService,
        private EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $character = $this->gameService->getPlayerCharacter();
        $enemy = $this->gameService->generateEnemy();

        $output->writeln("LVL: {$character->getLevel()} XP: {$character->getXp()}");
        $output->write('HP du personnage: ' . $character->getHealth() . ' ' . 'HP de l\'ennemi: ' . $enemy->getHealth());
        $output->writeln('');
        $output->writeln("⚔️ Combat starts: {$character->getName()} vs {$enemy->getNameAsString()}");

        $winner = $this->gameService->fight($character, $enemy, $output);

        $playerWon = $character->getHealth() > 0 && $enemy->getHealth() <= 0;
        
        if ($playerWon) {
            $this->characterService->calculateXp($character->getLevel(), $character->getXp(), $character->getHealth(), $enemy, $character, $this->entityManager);
            $output->writeln("💪 Vous avez gagné XP! Total XP: {$character->getXp()}");
            
            if ($this->characterService->canLevelUp($character)) {
                $output->writeln("💪 You have leveled up!");
                $this->characterService->levelUp($character, $input, $output, $this->entityManager);
            }
            
            $output->writeln("🏆 Winner: You win");
        } else {
            $previousXp = $character->getXp();
            $this->characterService->calculateXp($character->getLevel(), $character->getXp(), -1, $enemy, $character, $this->entityManager);
            $lostXp = $previousXp - $character->getXp();
            $output->writeln("☠️ Vous êtes mort et avez perdu {$lostXp} XP. XP restant: {$character->getXp()}");
            $output->writeln("🏆 Winner: Vous avez perdu");
        }

        return Command::SUCCESS;
    }
}
