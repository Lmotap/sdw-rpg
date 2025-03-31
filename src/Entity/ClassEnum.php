<?php

namespace App\Entity;

enum ClassEnum: string
{
    case WARRIOR = 'Warrior';
    case MAGE = 'Mage';
    case ROGUE = 'Rogue';
    case HEALER = 'Healer';
}
