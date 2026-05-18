<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

enum ItemCategory: string
{
    case SWORD = 'sword';
    case ARMOR = 'armor';
    case SHIELD = 'shield';
    case GLOVES = 'gloves';
}
