<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\Braden;
use App\Doctrine\DBAL\Type\VotiBraden13;
use App\Doctrine\DBAL\Type\VotiBraden14;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BradenFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $VotiBraden14 = new VotiBraden14();
        $votiBraden14Choices = $VotiBraden14->getValues();
        $VotiBraden13 = new VotiBraden13();
        $votiBraden13Choices = $VotiBraden13->getValues();

        $builder
            ->add('dataValutazione', DateType::class,[
                'widget' => 'single_text',  
                'empty_data' => 0,
            ])
            ->add('percezioneSensoriale', ChoiceType::class,[
                'choices' => $votiBraden14Choices
            ])
            ->add('umidita', ChoiceType::class,[
                'choices' => $votiBraden14Choices
            ])
            ->add('attivita', ChoiceType::class,[
                'choices' => $votiBraden14Choices
            ])
            ->add('mobilita', ChoiceType::class,[
                'choices' => $votiBraden14Choices
            ])
            ->add('nutrizione', ChoiceType::class,[
                'choices' => $votiBraden14Choices
            ])
            ->add('frizioneScivolamento', ChoiceType::class,[
                'choices' => $votiBraden13Choices
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Braden::class,
        ]);
    }
}
