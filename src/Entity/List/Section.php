<?php

namespace App\Entity\List;

use App\Repository\List\SectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
#[ORM\Table(name: 'section')]
class Section
{
    #[ORM\Id]
    #[ORM\Column(name: 'sectionId', type: 'string', length: 36)]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Doyenne::class)]
    #[ORM\JoinColumn(name: 'doyenneId', referencedColumnName: 'doyenneId')]
    private ?Doyenne $doyenne = null;

    #[ORM\Column(name: 'sectionNom', length: 100)]
    private string $nom;

    #[ORM\Column(name: 'sectionCode', length: 20)]
    private string $code;

    #[ORM\Column(name: 'sectionActive', type: 'boolean')]
    private bool $active;

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getCode(): string { return $this->code; }
    public function getDoyenne(): ?Doyenne { return $this->doyenne; }
}
