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
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'help' => "-0: 
                            Si inclina o scivola dalla sedia.
                            <br>-1: 
                            E' stabile, sicuro.
                ",
                'help_html' => true
            ])
            ->add('sedia',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'help' => "-0: 
                E' incapace senza aiuto.
                <br>-1: 
                Deve aiutarsi con le braccia.
                <br>-2: 
                Si alza senza aiutarsi con le braccia.
                ",
                'help_html' => true
            ])
            ->add('alzarsi', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'help' => "-0: 
                E’ incapace senza aiuto
                <br>-1: 
                Capace ma richiede più di un tentativo
                <br>-2: 
                Capace al primo tentativo
                ",
                'help_html' => true
            ])
            ->add('stazioneEretta', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'help' => "-0: 
                Instabile (vacilla, muove i piedi, oscilla il tronco)
                <br>-1: 
                Stabile grazie all’ausilio di un bastone o altri ausili
                <br>-2: 
                Stabile senza ausili per il cammino
                ",
                'help_html' => true
            ])
            ->add('stazioneErettaProlungata', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'help' => "-0: 
                Instabile (vacilla, muove i piedi, oscilla il tronco)
                <br>-1: 
                Stabile ma a base larga (malleoli mediali dist. > 10 cm)
                <br>-2: 
                Stabile a base stretta senza supporti
                ",
                'help_html' => true
            ])
            ->add('romberg',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'help' => "-0: 
                Instabile
                <br>-1: 
                Stabile
                ",
                'help_html' => true
            ])
            ->add('rombergSensibilizzato', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'help' => "-0: 
                Incomincia a cadere
                <br>-1: 
                Oscilla ma si riprende da solo
                <br>-2: 
                Stabile
                ",
                'help_html' => true
            ])
            ->add('girarsi1', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'label' => 'Girarsi a 360 gradi A',
                'help' => "-0: 
                A passi discontinui
                <br>-1: 
                A passi continui
                ",
                'help_html' => true
            ])
            ->add('girarsi2', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'label' => 'Girarsi a 360 gradi B',
                'help' => "-0: 
                Instabile (si aggrappa, oscilla)
                <br>-1: 
                Stabile
                ",
                'help_html' => true
            ])
            ->add('sedersi', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'help' => "-0: 
                Insicuro (sbaglia la distanza, cade sulla sedia)
                <br>-1: 
                Usa le braccia o ha un movimento discontinuo
                <br>-2: 
                Sicuro, movimenti continui
                ",
                'help_html' => true
            ])
            ->add('inizioDeambulazione',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'help' => "-0: 
                Una certa esitazione o più tentativi
                <br>-1: 
                Nessuna esitazione
                ",
                'help_html' => true
            ])
            ->add('piedeDx',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'label' => 'Lunghezza passo piede destro',
                'help' => "-0: 
                Durante il passo il piede dx non supera il sx
                <br>-1: 
                Il piede dx supera il sx
                ",
                'help_html' => true
            ])
            ->add('piedeDx2',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'label' => 'Altezza passo piede destro',
                'help' => "-0: 
                Il piede dx non si alza completamente dal pavimento
                <br>-1: 
                Il piede dx si alza completamente dal pavimento
                ",
                'help_html' => true
            ])
            ->add('piedeSx',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'label' => 'Lunghezza passo piede sinistro',
                'help' => "-0: 
                Durante il passo il piede sx non supera il dx
                <br>-1: 
                Il piede sx supera il dx
                ",
                'help_html' => true
            ])
            ->add('piedeSx2',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'label' => 'Altezza passo piede sinistro',
                'help' => "-0: 
                Il piede sx non si alza completamento dal pavimento
                <br>-1: 
                Il piede sx si alza completamento dal pavimento
                ",
                'help_html' => true
            ])
            ->add('simmetriaPasso',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'help' => "-0: 
                Il passo dx e sx non sembrano uguali
                <br>-1: 
                Il passo dx e sx sembrano uguali
                ",
                'help_html' => true
            ])
            ->add('continuitaPasso',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'help' => "-0: 
                Interrotto o discontinuo
                <br>-1: 
                Continuo
                ",
                'help_html' => true
            ])
            ->add('traiettoria', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti02Choices,
                'help' => "-0: 
                Marcata deviazione
                <br>-1: 
                Lieve o modesta deviazione o uso di ausili
                <br>-2: 
                Assenza di deviazione o uso di ausili
                ",
                'help_html' => true
            ])
            ->add('tronco', ChoiceType::class,[
                'placeholder' => '',
                'choices' =>$votiTinetti02Choices,
                'help' => "-0: 
                Marcata oscillazione o uso di ausili
                <br>-1: 
                Nessuna oscillazione ma flessione di gambe, ginocchia schiena o allargamento delle braccia durante il cammino
                <br>-2: 
                Nessuna oscillazione, flessione o uso di ausili
                ",
                'help_html' => true
            ])
            ->add('cammino',  ChoiceType::class,[
                'placeholder' => '',
                'choices' => $votiTinetti01Choices,
                'help' => "-0: 
                I talloni sono separati
                <br>-1: 
                I talloni quasi si toccano durante il cammino
                ",
                'help_html' => true
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
