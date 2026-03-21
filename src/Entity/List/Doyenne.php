<?php

namespace App\Entity\List;

use App\Repository\List\DoyenneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoyenneRepository::class)]
#[ORM\Table(name: 'doyenne')]
class Doyenne
{
    #[ORM\Id]
    #[ORM\Column(name: 'doyenneId', type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(name: 'doyenneNom', length: 100)]
    private string $nom;

    #[ORM\ManyToOne(targetEntity: Vicariat::class)]
    #[ORM\JoinColumn(name: 'vicariatId', referencedColumnName: 'vicariatId')]
    private ?Vicariat $vicariat = null;

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getVicariat(): ?Vicariat { return $this->vicariat; }
}
