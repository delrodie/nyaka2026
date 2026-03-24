<?php

namespace App\Filter;

use App\Entity\Main\Vicariat;
use App\Form\Type\Admin\VicariatFilterType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VicariatFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName = 'vicariat', string $label = 'Vicariat'): self
    {
        return new self()
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(VicariatFilterType::class)
            ;
    }

    public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
    {
        $queryBuilder
            ->join('entity.section', 'fSection2')
            ->join('fSection2.doyenne', 'fDoyenne')
            ->andWhere('fDoyenne.vicariat = :filterVicariat')
            ->setParameter('filterVicariat', $filterDataDto->getValue());
    }
}
