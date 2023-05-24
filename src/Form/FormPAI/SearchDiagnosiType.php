<?php

namespace App\Form\FormPAI;

use App\Entity\Diagnosi;
use App\Repository\DiagnosiRepository;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField(route: 'ux_entity_autocomplete_admin')]
class SearchDiagnosiType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class'=> Diagnosi::class,
            'label' => 'Diagnosi professionale',
            'help' => 'Classificazione secondo standard ICD-9-CM',
            'choice_label' => 'descrizione',
            'multiple'=> true,
            'required'   => false,
            'max_results' => 25,
            'filter_query' => function(QueryBuilder $qb, string $query, DiagnosiRepository $repository) {
                if (!$query) {
                    return;
                }
        
                $qb->andWhere('entity.descrizione LIKE :filter')
                    ->setParameter('filter', '%'.$query.'%');   
                
            },
            /*  Da utilizzare in alternativa a filter query o insieme a filter query specificando 
                al posto di entity il nome diagnosi e togliendo qb come parametro

                'query_builder' => function(DiagnosiRepository $diagnosiRepository) {
                return $diagnosiRepository->createQueryBuilder('diagnosi');
            },*/
            // serchable_fild è un altra alternativa molto più semplice in caso di un unica entity
            // da utilizzare come alias di ricerca

            //'security' => 'ROLE_SOMETHING',
            
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
