<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\Tinetti;
use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\VotiTinetti01;
use App\Doctrine\DBAL\Type\VotiTinetti02;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TinettiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $VotiTinetti01 = new VotiTinetti01();
        $votiTinetti01Choices = $VotiTinetti01->getValues();
        $VotiTinetti02 = new VotiTinetti02();
        $votiTinetti02Choices = $VotiTinetti02->getValues();

        $builder
            ->add('dataValutazione', DateType::class,[
                'widget' => 'single_text',  
                'empty_data' => 0,
            ])
            ->add('equilibrioSeduto', ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('sedia',  ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('alzarsi', ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('stazioneEretta', ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('stazioneErettaProlungata', ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('romberg',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('rombergSensibilizzato', ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('girarsi1', ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('girarsi2', ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('sedersi', ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('inizioDeambulazione',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('piedeDx',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('piedeDx2',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('piedeSx',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('piedeSx2',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('simmetriaPasso',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('continuitaPasso',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
            ->add('traiettoria', ChoiceType::class,[
                'choices' => $votiTinetti02Choices
            ])
            ->add('tronco', ChoiceType::class,[
                'choices' =>$votiTinetti02Choices
            ])
            ->add('cammino',  ChoiceType::class,[
                'choices' => $votiTinetti01Choices
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tinetti::class,
        ]);
    }
}
