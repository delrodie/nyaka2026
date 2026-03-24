<?php

namespace App\Controller\Admin;

use App\Entity\Main\Doyenne;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DoyenneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Doyenne::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('vicariat'),
            TextField::new('nom'),
            TextField::new('code'),
        ];
    }

}
