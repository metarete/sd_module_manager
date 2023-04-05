<?php

namespace App\Form;

use App\Entity\Obiettivi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObiettiviFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('titolo', null, [
                'label' => 'Titolo',
                'empty_data' => '',
            ])
            ->add('descrizione', null, [
                'label' => 'Descrizione',
                'empty_data' => '',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Obiettivi::class,
        ]);
    }
}