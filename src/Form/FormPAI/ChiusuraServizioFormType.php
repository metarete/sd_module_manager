<?php

namespace App\Form\FormPAI;

use App\Doctrine\DBAL\Type\MotivazioneChiusuraForzata;
use Symfony\Component\Form\AbstractType;
use App\Entity\EntityPAI\ChiusuraServizio;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChiusuraServizioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $MotivazioneChiusura = new MotivazioneChiusuraForzata();
        $motivazioneChiusuraChoices = $MotivazioneChiusura->getValues();

        $builder
            ->add('dataValutazione', DateType::class,[
                'widget' => 'single_text',  
                'empty_data' => 0,
            ])
            ->add('conclusioni', TextareaType::class, [
                'attr' => array('style' => 'height:100px'),
            ])
            ->add('motivazioneChiusura', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $motivazioneChiusuraChoices
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChiusuraServizio::class,
        ]);
    }
}
