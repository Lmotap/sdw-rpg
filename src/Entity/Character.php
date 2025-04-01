<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $name;

    #[ORM\Column]
    private int $strength;

    #[ORM\Column]
    private int $constitution;

    #[ORM\Column(length: 50)]
    private ClassEnum $class;

    #[ORM\Column]
    private int $xp;

    #[ORM\Column]
    private int $health;

    #[ORM\Column]
    private int $level;

    public function __construct(
        string $name,
        int $strength,
        int $constitution,
        ClassEnum $class,
        int $xp = 0,
        int $level = 0,
    )
    {
        $this->name = $name;
        $this->strength = $strength;
        $this->class = $class;
        $this->constitution = $constitution;
        $this->xp = $xp;
        $this->level = $level;
        $this->health = 10 + ($this->getConstitution() * 2) + ($this->getLevel() * 2);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClass(): ClassEnum
    {
        return $this->class;
    }

    public function setName(string $name): self
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

    public function getXp(): int
    {
        return $this->xp;
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

    public function setClass(ClassEnum $class): self
    {
        $this->class = $class;
        return $this;
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
        if ($health < 0) {
            $this->health = 0;
            return 0;
        }

        $this->health = $health;
        return $health;
    }

    public function setXp(int $xp): void
    {
        $this->xp = $xp;
    }

    public function addXp(int $xp): void
    {
        $newXp = $this->getXp() + $xp;

        $this->xp = $newXp;
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
