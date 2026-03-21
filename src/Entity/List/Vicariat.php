<?php

namespace App\Entity\List;

use App\Repository\List\VicariatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VicariatRepository::class)]
#[ORM\Table(name: 'vicariat')]
class Vicariat
{
    #[ORM\Id]
    #[ORM\Column(name: 'vicariatId', type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(name: 'vicariatNom', length: 100)]
    private string $nom;

    #[ORM\Column(name: 'vicariatCode', length: 20)]
    private string $code;

    #[ORM\Column(name: 'vicariatActive', type: 'boolean')]
    private bool $active = true;

    // Getters
    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getCode(): string { return $this->code; }
    public function isActive(): bool { return $this->active; }
}
