<?php

namespace App\Controller\Admin;

use App\Entity\Main\Participant;
use App\Filter\VicariatFilter;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;

class ParticipantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Participant::class;
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto                                                $entityDto,
        FieldCollection                                          $fields,
        FilterCollection                                         $filters
    ): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $queryBuilder
            ->andWhere('entity.wavePaymentStatus = :status')
            ->setParameter('status', 'processing')
//            ->setParameter('status', 'succeeded')
        ;

        return $queryBuilder;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::EDIT, Action::DELETE, Action::NEW);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(VicariatFilter::new())
            ->add('section')
            ->add('grade')
            ->add('traitement')
            ->add('profil')
            ->add('taille')
            ->add('wavePaymentStatus')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('section'),
            TextField::new('doyenne')->hideOnForm(),
            TextField::new('vicariat')->hideOnForm(),
            TextField::new('matricule'),
            TextField::new('nomPrenoms'),
            TextField::new('genre'),
            NumberField::new('age'),
            AssociationField::new('grade'),
            TextField::new('declarantNom'),
            TextField::new('declarantContact'),
            TextField::new('traitement'),
            TextField::new('profil'),
            TextField::new('taille'),
            NumberField::new('montant'),
            DateTimeField::new('waveWhenCompleted', 'Date')
        ];
    }

}
