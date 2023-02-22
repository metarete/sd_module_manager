<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\Barthel;
use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\VotiBarthel05;
use App\Doctrine\DBAL\Type\VotiBarthel010;
use App\Doctrine\DBAL\Type\VotiBarthel015;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BarthelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $VotiBarthel010 = new VotiBarthel010();
        $votiBarthel010Choices = $VotiBarthel010->getValues();
        $VotiBarthel05 = new VotiBarthel05();
        $votiBarthel05Choices = $VotiBarthel05->getValues();
        $VotiBarthel015 = new VotiBarthel015();
        $votiBarthel015Choices = $VotiBarthel015->getValues();

        $builder
            ->add('dataValutazione', DateType::class,[
                'widget' => 'single_text',  
                'empty_data' => 0,
            ])
            ->add('alimentazione', ChoiceType::class,[
                'choices' => $votiBarthel010Choices
            ])
            ->add('bagnoDoccia', ChoiceType::class,[
                'choices' => $votiBarthel05Choices
            ])
            ->add('igienePersonale',ChoiceType::class,[
                'choices' => $votiBarthel05Choices
            ])
            ->add('abbigliamento', ChoiceType::class,[
                'choices' => $votiBarthel010Choices
            ])
            ->add('continenzaIntestinale', ChoiceType::class,[
                'choices' => $votiBarthel010Choices
            ])
            ->add('continenzaUrinaria', ChoiceType::class,[
                'choices' => $votiBarthel010Choices
            ])
            ->add('toilet', ChoiceType::class,[
                'choices' => $votiBarthel010Choices
            ])
            ->add('trasferimentoLettoSedia', ChoiceType::class,[
                'choices' => $votiBarthel015Choices
            ])
            ->add('scale', ChoiceType::class,[
                'choices' => $votiBarthel010Choices
            ])
            ->add('deambulazione')
            ->add('deambulazioneValida', ChoiceType::class,[
                'choices' => $votiBarthel015Choices
            ])
            ->add('usoCarrozzina', ChoiceType::class,[
                'choices' => $votiBarthel05Choices
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Barthel::class,
        ]);
    }
}
