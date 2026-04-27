<?php

namespace App\Controller\Admin;

use App\Entity\Main\Cloture;
use App\Repository\Main\ClotureRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClotureCrudController extends AbstractCrudController
{
    public function __construct(
        private ClotureRepository $clotureRepository
    ) {}

    public static function getEntityFqcn(): string
    {
        return Cloture::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $hasRecord = $this->clotureRepository->count([]) > 0;

        if ($hasRecord) {
            // Désactiver le bouton "Créer Cloture" si un enregistrement existe déjà
            $actions->disable(Action::NEW);
        }

        return $actions;
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }*/
}
