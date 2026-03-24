<?php

namespace App\Entity\Main;

use App\Repository\Main\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Main\Doyenne;
use App\Entity\Main\Vicariat;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomPrenoms = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\ManyToOne]
    private ?Section $section = null;

    #[ORM\ManyToOne]
    private ?Grade $grade = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $declarantNom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $declarantContact = null;

    #[ORM\Column(nullable: true)]
    private ?int $montant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $taille = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profil = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $traitement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $waveId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $waveCheckoutStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $waveClientReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wavePaymentStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $waveTransactionId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $waveWhenCompleted = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $waveWhenCreated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNomPrenoms(): ?string
    {
        return $this->nomPrenoms;
    }

    public function setNomPrenoms(?string $nomPrenoms): static
    {
        $this->nomPrenoms = $nomPrenoms;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): static
    {
        $this->section = $section;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getDeclarantNom(): ?string
    {
        return $this->declarantNom;
    }

    public function setDeclarantNom(?string $declarantNom): static
    {
        $this->declarantNom = $declarantNom;

        return $this;
    }

    public function getDeclarantContact(): ?string
    {
        return $this->declarantContact;
    }

    public function setDeclarantContact(?string $declarantContact): static
    {
        $this->declarantContact = $declarantContact;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(?string $profil): static
    {
        $this->profil = $profil;

        return $this;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(?string $traitement): static
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getWaveId(): ?string
    {
        return $this->waveId;
    }

    public function setWaveId(?string $waveId): static
    {
        $this->waveId = $waveId;

        return $this;
    }

    public function getWaveCheckoutStatus(): ?string
    {
        return $this->waveCheckoutStatus;
    }

    public function setWaveCheckoutStatus(?string $waveCheckoutStatus): static
    {
        $this->waveCheckoutStatus = $waveCheckoutStatus;

        return $this;
    }

    public function getWaveClientReference(): ?string
    {
        return $this->waveClientReference;
    }

    public function setWaveClientReference(?string $waveClientReference): static
    {
        $this->waveClientReference = $waveClientReference;

        return $this;
    }

    public function getWavePaymentStatus(): ?string
    {
        return $this->wavePaymentStatus;
    }

    public function setWavePaymentStatus(?string $wavePaymentStatus): static
    {
        $this->wavePaymentStatus = $wavePaymentStatus;

        return $this;
    }

    public function getWaveTransactionId(): ?string
    {
        return $this->waveTransactionId;
    }

    public function setWaveTransactionId(?string $waveTransactionId): static
    {
        $this->waveTransactionId = $waveTransactionId;

        return $this;
    }

    public function getWaveWhenCompleted(): ?string
    {
        return $this->waveWhenCompleted;
    }

    public function setWaveWhenCompleted(?string $waveWhenCompleted): static
    {
        $this->waveWhenCompleted = $waveWhenCompleted;

        return $this;
    }

    public function getWaveWhenCreated(): ?string
    {
        return $this->waveWhenCreated;
    }

    public function setWaveWhenCreated(?string $waveWhenCreated): static
    {
        $this->waveWhenCreated = $waveWhenCreated;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDoyenne(): ?Doyenne
    {
        return $this->section?->getDoyenne();
    }

    public function getVicariat(): ?Vicariat
    {
        return $this->section?->getDoyenne()?->getVicariat();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

}
