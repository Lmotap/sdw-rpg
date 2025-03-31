<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Character;
use App\Service\GameService;
use App\Service\CharacterService;
use App\Entity\Enemy;
use App\Entity\EnemiesNameEnum;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class GameTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public function testGame(): void
    {
        $this->assertTrue(true);
    }
}