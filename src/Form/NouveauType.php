<?php

namespace App\Form;

use App\Entity\Main\Grade;
use App\Entity\Main\Participant;
use App\Entity\Main\Section;
use App\Form\Main\GradeAutocompleteField;
use App\Form\Main\SectionAutocompleteField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NouveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPrenoms', TextType::class,[
                'attr' => ['class' => 'form-control rounded-0', 'autocomplete'=>'off'],
                'label_attr' => ['class' => 'form-label  fst-italic text-muted'],
                'label_html' => true,
                'label' => "Nom & prénoms  <sup class='text-danger'>*</sup>"
            ])
            ->add('genre', ChoiceType::class,[
                'attr' =>['class' => 'form-select rounded-0'],
                'choices' => [
                    '-- Sélectionnez --' => '',
                    'Féminin' => 'F',
                    'Masculin' => 'M'
                ]
            ])
            ->add('age', ChoiceType::class,[
                'attr' =>['class' => 'form-control rounded-0'],
                'choices' => [
                    '-- Sélectionnez --' => '',
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                    7 => 7,
                    8 => 8,
                    9 => 9,
                    10 => 10,
                    11 => 11,
                    12 => 12,
                    13 => 13,
                    14 => 14,
                    15 => 15,
                ],
//                'autocomplete' => true
            ])
            ->add('declarantNom', TextType::class,[
                'attr' => ['class' => 'form-control rounded-0', 'autocomplete'=>'off'],
                'label_attr' => ['class' => 'form-label  fst-italic text-muted'],
                'label_html' => true,
                'label' => "Nom & prénoms du déclarant  <sup class='text-danger'>*</sup>"
            ])
            ->add('declarantContact', TelType::class,[
                'attr' => ['class' => 'form-control rounded-0', 'autocomplete'=>'off'],
                'label_attr' => ['class' => 'form-label  fst-italic text-muted'],
                'label_html' => true,
                'label' => "Contact du déclarant  <sup class='text-danger'>*</sup>"
            ])
            //->add('taille')
//            ->add('profil', ChoiceType::class,[
//                'attr' => ['class' => 'form-select rounded-0'],
//                'choices' => [
//                    'Participant simple' => "Participant simple",
//                    "Comité d'organisation" => "Comité d'organisation"
//                ],
//                'label_attr' => ['class' => 'form-label  fst-italic text-muted'],
//                'label_html' => true,
//                'label' => "Profil  <sup class='text-danger'>*</sup>"
//            ])
            ->add('traitement', ChoiceType::class,[
                'attr' =>['class' => 'form-select rounded-0'],
                'choices' => [
                    '-- Sélectionnez --' => '',
                    'OUI' => 'OUI',
                    'NON' => 'NON'
                ],
                'label_attr' => ['class' => 'form-label  fst-italic text-muted'],
                'label_html' => true,
                'label' => "Êtes-vous sur traitement médical?   <sup class='text-danger'>*</sup>"
            ])
            ->add('section', SectionAutocompleteField::class)
//            ->add('grade', GradeAutocompleteField::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
