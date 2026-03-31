<?php

namespace App\Form;

use App\Entity\Main\Doyenne;
use App\Entity\Main\Section;
use App\Form\Main\DoyenneAutocompleteField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchByDoyenneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('doyenne', DoyenneAutocompleteField::class,)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}
