<?php

namespace App\Form\FormPAI;

use App\Doctrine\DBAL\Type\MotivazioneChiusuraForzata;
use App\Entity\EntityPAI\ChiusuraForzata;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChiusuraForzataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $MotivazioneChiusuraForzata = new MotivazioneChiusuraForzata();
        $motivazioneChiusuraForzataChoices = $MotivazioneChiusuraForzata->getValues();

        $builder
            ->add('data', DateType::class, [
                'widget' => 'single_text',
                'empty_data' => 0,
            ])
            ->add('conclusioni', TextareaType::class, [
                'attr' => array('style' => 'height:100px'),
            ])
            ->add('motivazioneChiusuraForzata', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $motivazioneChiusuraForzataChoices
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChiusuraForzata::class,
        ]);
    }
}
