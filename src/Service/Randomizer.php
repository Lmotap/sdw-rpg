<?php

namespace App\Service;

class Randomizer
{
    public function rand(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}