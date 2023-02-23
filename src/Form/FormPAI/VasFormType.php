<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\Vas;
use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\VotiRilevazioneVas;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VasFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
                'choices' => $votiRilevazioneVasChoices,
                'label' => 'Rilevazione del dolore prima del trattamento',
            ])
            ->add('pz', ChoiceType::class,[
                'choices' => $votiRilevazioneVasChoices,
                'label' => 'Rilevazione del dolore durante il trattamento',
            ])
            ->add('esito', ChoiceType::class,[
                'choices' => $votiRilevazioneVasChoices,
                'label' => 'Rilevazione  del dolore a fine trattamento',
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
