<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
class Enemy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private EnemiesNameEnum $name;

    #[ORM\Column]
    private int $strength;

    #[ORM\Column]
    private int $constitution;

    #[ORM\Column]
    private int $health;

    #[ORM\Column]
    private int $level;

    public function __construct(
        EnemiesNameEnum $name,
        int             $strength,
        int             $constitution,
        int             $xp = 0,
        int             $level = 0,
    )
    {
        $this->name = $name;
        $this->strength = $strength;
        $this->constitution = $constitution;
        $this->xp = $xp;
        $this->level = $level;
        $this->health = 10 + ($this->getConstitution() * 2) + ($this->getLevel() * 2);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): EnemiesNameEnum
    {
        return $this->name;
    }

    public function getNameAsString(): string
    {
        return $this->name->value;
    }

    public function getRandomEnemy(): int
    {
        return array_rand(EnemiesNameEnum::cases());
    }

    public function setName(EnemiesNameEnum $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;
        return $this;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;
        return $this;
    }

    public function getConstitution(): int
    {
        return $this->constitution;
    }

    public function setConstitution(int $constitution): self
    {
        $this->constitution = $constitution;
        return $this;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function setHealth(int $health): int
    {
        $this->health = $health;
        return $health;
    }

    public function getAttack(): int
    {
        return 2 + ($this->getStrength() * 1) + ($this->getLevel() * 1);
    }

    public function getDefense(): int
    {
        return 1 + ($this->getConstitution() * 0.5) + ($this->getLevel() * 0.5);
    }
}
