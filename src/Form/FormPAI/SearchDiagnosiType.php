<?php

namespace App\Form;

use App\Entity\Diagnosi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class SearchDiagnosiType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class'=> Diagnosi::class,
            'label' => 'Diagnosi professionale',
            'help' => 'Classificazione secondo standard ICD-9-CM',
            'multiple'=> true,
            'required'   => false,
            'max_results' => 25,
            'filter_query'=> [
                'filter_query' => function(QueryBuilder $qb, string $query, DiagnosiRepository $repository) {
                    if (!$query) {
                        return;
                    }
            
                    $qb->andWhere('entity.name LIKE :filter OR entity.description LIKE :filter')
                        ->setParameter('filter', '%'.$query.'%');
                },
            ],
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
