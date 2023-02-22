<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\Vas;
use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\VotiRilevazioneVas;
use App\Doctrine\DBAL\Type\VotiMonitoraggioVas;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VasFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $VotiMonitoraggioVas = new VotiMonitoraggioVas();
        $votiMonitoraggioVasChoices = $VotiMonitoraggioVas->getValues();
        $VotiRilevazioneVas = new VotiRilevazioneVas();
        $votiRilevazioneVasChoices = $VotiRilevazioneVas->getValues();
        
        $builder
            ->add('data', DateType::class,[
                'widget' => 'single_text',  
                'empty_data' => 0,
            ])
            ->add('ora', TimeType::class, [
                'widget' => 'single_text',
                'empty_data' => '00',
                'attr'=>array
                ('class'=>'timepicker')
            ])
            ->add('base2', ChoiceType::class,[
                'choices' => $votiRilevazioneVasChoices
            ])
            ->add('pz', ChoiceType::class,[
                'choices' => $votiRilevazioneVasChoices
            ])
            ->add('esito', ChoiceType::class,[
                'choices' => $votiRilevazioneVasChoices
            ])
            ->add('rilevanzaMonitoraggio', ChoiceType::class,[
                'choices' => $votiMonitoraggioVasChoices
            ])
            ->add('farmaci')
            ->add('altro')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vas::class,
        ]);
    }
}
