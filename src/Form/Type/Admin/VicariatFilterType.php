<?php

namespace App\Form\Type\Admin;

use App\Entity\Main\Vicariat;
use App\Repository\Main\VicariatRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VicariatFilterType extends AbstractType
{
    public function __construct(private readonly VicariatRepository $vicariatRepository)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class'        => Vicariat::class,
            'choices'      => $this->vicariatRepository->findAll(),
            'choice_label' => 'nom',
            'placeholder'  => 'Tous les vicariats',
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}
