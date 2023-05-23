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
            'choice_label' => function (Diagnosi $diagnosi) {
                return $diagnosi->getDescrizione();
                },
            'label' => 'Diagnosi professionale',
            'help' => 'Classificazione secondo standard ICD-9-CM',
            'multiple'=> true,
            'required'   => false,
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
