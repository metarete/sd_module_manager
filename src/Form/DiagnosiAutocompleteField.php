<?php

namespace App\Form;

use App\Entity\Diagnosi;
use App\Repository\DiagnosiRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class DiagnosiAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Diagnosi::class,
            'placeholder' => 'Choose a Diagnosi',
            'choice_label' => 'descrizione',

            'query_builder' => function(DiagnosiRepository $diagnosiRepository) {
                return $diagnosiRepository->createQueryBuilder('diagnosi');
            },
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
