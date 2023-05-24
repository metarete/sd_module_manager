<?php

namespace App\Form;

use App\Entity\Diagnosi;
use App\Repository\DiagnosiRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField(alias: 'diagnosi', route: 'ux_entity_autocomplete_admin')]
class SearchDiagnosiType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class'=> Diagnosi::class,
            //'label' => 'Diagnosi professionale',
            //'help' => 'Classificazione secondo standard ICD-9-CM',
            //'multiple'=> true,
            //'required'   => false,
            //'max_results' => 25,
            /*'filter_query' => function(QueryBuilder $qb, string $query, DiagnosiRepository $repository) {
                if (!$query) {
                    return;
                }
        
                $qb->andWhere('Diagnosi.descrizione LIKE :filter')
                    ->setParameter('filter', '%'.$query.'%');   
                 
            },*/
            
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
