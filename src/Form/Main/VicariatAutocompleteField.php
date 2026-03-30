<?php

namespace App\Form\Main;

use App\Entity\Main\Vicariat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class VicariatAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Vicariat::class,
            'placeholder' => 'Choisissez le Vicariat...',
             'choice_label' => 'nom',

            // choose which fields to use in the search
            // if not passed, *all* fields are used
             'searchable_fields' => ['nom'],

            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
