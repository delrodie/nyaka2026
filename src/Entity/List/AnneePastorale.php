<?php

namespace App\Entity\List;

use App\Repository\List\AnneePastoraleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneePastoraleRepository::class)]
#[ORM\Table(name: 'annee_pastorale')]
class AnneePastorale
{
    #[ORM\Id]
    #[ORM\Column(name: 'anneePastoraleId', type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(name: 'anneePastoraleNom', length: 100)]
    private string $nom;

    #[ORM\Column(name: 'anneePastoraleActive', type: 'boolean')]
    private bool $active;

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function isActive(): bool { return $this->active; }
}
