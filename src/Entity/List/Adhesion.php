<?php

namespace App\Entity\List;

use App\Repository\List\AdhesionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdhesionRepository::class)]
#[ORM\Table(name: 'adhesion')]
class Adhesion
{
    #[ORM\Id]
    #[ORM\Column(name: 'adhesionId', type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(name: 'adhesionIdUnique', length: 255)]
    private string $matricule; // Ton matricule de recherche

    #[ORM\Column(name: 'adhesionNomPrenoms', length: 50)]
    private string $nomPrenoms;

    #[ORM\Column(name: 'adhesionGenre', type: 'string', columnDefinition: "ENUM('M', 'F', 'P')")]
    private string $genre;

    #[ORM\Column(name: 'adhesionAge', type: 'integer', nullable: true)]
    private ?int $age;

    #[ORM\ManyToOne(targetEntity: Section::class)]
    #[ORM\JoinColumn(name: 'sectionId', referencedColumnName: 'sectionId')]
    private ?Section $section = null;

    #[ORM\ManyToOne(targetEntity: Grade::class)]
    #[ORM\JoinColumn(name: 'gradeId', referencedColumnName: 'gradeId')]
    private ?Grade $grade = null;

    #[ORM\ManyToOne(targetEntity: AnneePastorale::class)]
    #[ORM\JoinColumn(name: 'anneePastoraleId', referencedColumnName: 'anneePastoraleId')]
    private ?AnneePastorale $anneePastorale = null;

    // Getters
    public function getId(): string { return $this->id; }
    public function getMatricule(): string { return $this->matricule; }
    public function getNomPrenoms(): string { return $this->nomPrenoms; }
    public function getGenre(): string { return $this->genre; }
    public function getAge(): ?int { return $this->age; }
    public function getSection(): ?Section { return $this->section; }
    public function getGrade(): ?Grade { return $this->grade; }
    public function getAnneePastorale(): ?AnneePastorale { return $this->anneePastorale; }
}
