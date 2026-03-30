<?php

namespace App\Form;

use App\Entity\Main\Doyenne;
use App\Entity\Main\Vicariat;
use App\Form\Main\VicariatAutocompleteField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchByVicariatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vicariat', VicariatAutocompleteField::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doyenne::class,
        ]);
    }
}
