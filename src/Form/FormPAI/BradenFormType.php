<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\Braden;
use App\Doctrine\DBAL\Type\VotiBraden13;
use App\Doctrine\DBAL\Type\VotiBraden14;
use App\Entity\PresidiAntidecubito;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'placeholder' => '',
                'choices' => $votiBraden14Choices,
                'help' => "-4: Non limitata.
                            <br>-3: Leggermente Limitata.
                            <br>-2: Molto Limitata.
                            <br>-1: Completamente Limitata.
                            ",
                'help_html' => true
            ])
            ->add('umidita', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiBraden14Choices,
                'help' => "-4: Raramente bagnato.
                            <br>-3: Occasionalmente bagnato.
                            <br>-2: Spesso bagnato.
                            <br>-1: Costantemente bagnato.
                ",
                'help_html' => true
            ])
            ->add('attivita', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiBraden14Choices,
                'help' => "-4: Cammina frequentemente.
                            <br>-3: Cammina occasionalmente.
                            <br>-2: In poltrona.
                            <br>-1: Completamente allettato.
                ",
                'help_html' => true
            ])
            ->add('mobilita', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiBraden14Choices,
                'help' => "-4: Limitazioni assenti.
                            <br>-3: Parzialmente limitato.
                            <br>-2: Molto limitato.
                            <br>-1: Completamente immobile.
                ",
                'help_html' => true
            ])
            ->add('nutrizione', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiBraden14Choices,
                'help' => "-4: Eccellente.
                            <br>-3: Adeguata.
                            <br>-2: Probabilmente inadeguata.
                            <br>-1: Molto povera.
                ",
                'help_html' => true
            ])
            ->add('frizioneScivolamento', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiBraden13Choices,
                'help' => "-3: Senza problemi apparenti.
                            <br>-2: Problema potenziale.
                            <br>-1: Problema.
                ",
                'help_html' => true
            ])
            ->add('presenzaPresidiAntidecubito', ChoiceType::class,[
                'placeholder' => '',
                'choices' => [
                    'Si' => 'Si',
                    'No' => 'No',
                ],
                'label' => 'Presenza Presidi Antidecubito'
            ])
            ->add('presidiAntidecubito', EntityType::class,[
                'class'=> PresidiAntidecubito::class,
                'choice_label' => function (PresidiAntidecubito $presidiAntidecubito) {
                    return $presidiAntidecubito->getNome();},
                'label' => 'Presidi Antidecubito',
                'label_attr' => ['class' => 'presidiAntidecubito_attr'],
                'multiple'=> true,
                'autocomplete' => true,
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
