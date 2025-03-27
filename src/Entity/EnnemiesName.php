<?php

namespace App\Entity;

enum EnnemiesName: string
{
    case GOBLIN = 'Goblin';
    case ORC = 'Orc';
    case TROLL = 'Troll';
    case DRAGON = 'Dragon';
    case DEMON = 'Demon';
    case VAMPIRE = 'Vampire';
    case WITCH = 'Witch';
    case SKELETON = 'Skeleton';
    case ZOMBIE = 'Zombie';
    case GHOUL = 'Ghoul';
}
