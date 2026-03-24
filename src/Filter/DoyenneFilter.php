<?php

namespace App\Filter;

use App\Entity\Main\Doyenne;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DoyenneFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName = 'doyenne', string $label = 'Doyenné'): self
    {
        return (new self())->initialize($propertyName, $label, [
            'value_type' => EntityType::class,
            'value_type_options' => [
                'class' => Doyenne::class,
            ],
        ]);
    }

    public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
    {
        $queryBuilder
            ->join('entity.section', 'fSection')
            ->andWhere('fSection.doyenne = :filterDoyenne')
            ->setParameter('filterDoyenne', $filterDataDto->getValue());
    }
}
