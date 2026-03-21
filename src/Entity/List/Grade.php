<?php

namespace App\Entity\List;

use App\Repository\List\GradeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
#[ORM\Table(name: 'grade')]
class Grade
{
    #[ORM\Id]
    #[ORM\Column(name: 'gradeId', type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(name: 'gradeNom', length: 100)]
    private string $nom;

    #[ORM\Column(name: 'gradeCode', length: 20)]
    private string $code;

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
}
