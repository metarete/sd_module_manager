<?php

namespace App\Form\FormPAI;

use App\Doctrine\DBAL\Type\VotiPainad02;
use App\Entity\EntityPAI\Painad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PainadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $VotiPainad02 = new VotiPainad02();
        $votiPainad02Choices = $VotiPainad02->getValues();

        $builder
        ->add('dataValutazione', DateType::class,[
            'widget' => 'single_text',  
            'empty_data' => 0,
        ])
        ->add('respiro', ChoiceType::class,[
            'placeholder' => '',
            'choices' => $votiPainad02Choices,
            'label' => 'Respiro',
            'help' => "-2: Respiro alterato, iperventilazione.
                        <br>-1: Respiro a tratti alterato, brevi periodi di iperventilazione.
                        <br>-0: Normale.
                        ",
            'help_html' => true
        ])
        ->add('vocalizzazione', ChoiceType::class,[
            'placeholder' => '',
            'choices' => $votiPainad02Choices,
            'label' => 'Vocalizzazione',
            'help' => "-2: Ripetuti richiami, lamenti, pianto.
                        <br>-1: Occasionali lamenti, saltuarie espressioni negative.
                        <br>-0: Nessuna.
                        ",
            'help_html' => true
        ])
        ->add('espressioneFacciale', ChoiceType::class,[
            'placeholder' => '',
            'choices' => $votiPainad02Choices,
            'label' => 'Espressione facciale',
            'help' => "-2: Smorfie.
                        <br>-1: Triste, ansiosa, contratta.
                        <br>-0: Sorridente o inespressiva.
                        ",
            'help_html' => true
        ])
        ->add('linguaggioDelCorpo', ChoiceType::class,[
            'placeholder' => '',
            'choices' => $votiPainad02Choices,
            'label' => 'Linguaggio del corpo',
            'help' => "-2: Ridigità, agitazione, ginocchia piegate, movimento afinalistico a scatti.
                        <br>-1: Teso, movimenti nervosi, irrequietezza.
                        <br>-0: Rilassato.
                        ",
            'help_html' => true
        ])
        ->add('consolabilita', ChoiceType::class,[
            'placeholder' => '',
            'choices' => $votiPainad02Choices,
            'label' => 'Consolabilità',
            'help' => "-2: Inconsolabile, non si distrae ne si rassicura.
                        <br>-1: Distratto o rassicurato da voce o tocco.
                        <br>-0: Non necessita di consolazione.
                        ",
            'help_html' => true
        ])
        ->add('salva', SubmitType::class, ['label' => 'Salva'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Painad::class,
        ]);
    }
}