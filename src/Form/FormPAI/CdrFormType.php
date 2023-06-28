<?php

namespace App\Form\FormPAI;

use App\Doctrine\DBAL\Type\DemenzaCdr;
use App\Entity\EntityPAI\Cdr;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CdrFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $demenza = new DemenzaCdr();
        $demenzaChoices = $demenza->getValues();

        $builder
            ->add('dataValutazione', DateType::class, [
                'widget' => 'single_text',
                'empty_data' => 0,
                'label' => 'Data di valutazione'
            ])
            ->add('memoria', ChoiceType::class, [
                'placeholder' => '',
                'label' => 'Memoria',
                'required'   => false,
                'choices' => $demenzaChoices,
                'help' => "-CDR 0: Memoria adeguata o smemoratezza occasionale
                            <br>-CDR 0,5: Lieve smemoratezza permanente; parziale rievocazione di eventi
                            <br>-CDR 1: Perdita di memoria modesta per eventi recenti; interferenza con attività quotidiane
                            <br>-CDR 2: Perdita di memoria severa; materiale nuovo perso rapidamente
                            <br>-CDR 3: Perdita di memoria grave; rimangono alcuni frammenti
                ",
                'help_html' => true
            ])
            ->add('orientamento', ChoiceType::class, [
                'placeholder' => '',
                'label' => 'Orientamento',
                'required'   => false,
                'choices' => $demenzaChoices,
                'help' => "-CDR 0: Perfettamente orientato
                            <br>-CDR 0,5: Perfettamente orientato
                            <br>-CDR 1: Alcune difficoltà nel tempo; possibile disorientamento topografico
                            <br>-CDR 2: Usualmente disorientamento temporale, spesso spaziale
                            <br>-CDR 3: Orientamento solo personale
                ",
                'help_html' => true
            ])
            ->add('giudizio', ChoiceType::class, [
                'placeholder' => '',
                'label' => 'Giudizio e soluzione di problemi',
                'required'   => false,
                'choices' => $demenzaChoices,
                'help' => "-CDR 0: Risolve bene i problemi giornalieri; giudizio adeguato rispetto al passato
                            <br>-CDR 0,5: Dubbia compromissione nella soluzione di problemi; analogie e differenze (prove di ragionamento)
                            <br>-CDR 1: Difficoltà moderata; esecuzione di problemi complessi; giudizio sociale adeguto
                            <br>-CDR 2: Difficoltà severa nell’esecuzione di problemi complessi; giudizio sociale compromesso
                            <br>-CDR 3: Incapace di dare giudizi o di risolvere problemi
                ",
                'help_html' => true
            ])
            ->add('attivitaSociali', ChoiceType::class, [
                'placeholder' => '',
                'label' => 'Attività sociali',
                'required'   => false,
                'choices' => $demenzaChoices,
                'help' => "-CDR 0: Attività indipendente e livelli usuali in lavoro, acquisti, pratiche burocraiche
                            <br>-CDR 0,5: Solo dubbia compromissione nelle attività descritte
                            <br>-CDR 1: Incapace di compiere indipendentemente le attività, a esclusione di attività facili
                            <br>-CDR 2: Nessuna pretesa di attività indipendente fuori casa; in grado di essere portato fuori casa
                            <br>-CDR 3: Nessuna pretesa di attività indipendente fuori casa; non in grado di uscire
                ",
                'help_html' => true
            ])
            ->add('casa', ChoiceType::class, [
                'placeholder' => '',
                'label' => 'Casa e tempo libero',
                'required'   => false,
                'choices' => $demenzaChoices,
                'help' => "-CDR 0: Vita domestica e interessi intellettuali conservati
                            <br>-CDR 0,5: Vita domestica e interessi intellettuali lievemente compromessi
                            <br>-CDR 1: Lieve ma sensibile compromissione della vita domestica; abbandono hobby e interessi
                            <br>-CDR 2: Interessi ridotti, non sostenuti, vita domestica ridotta a funzioni semplici
                            <br>-CDR 3: Nessuna funzionalità fuori dalla propria camera
                ",
                'help_html' => true
            ])
            ->add('curaPersonale', ChoiceType::class, [
                'placeholder' => '',
                'label' => 'Cura personale',
                'required'   => false,
                'choices' => $demenzaChoices,
                'help' => "-CDR 0: Interamente capace di curarsi della propria persona
                            <br>-CDR 0,5: Richiede facilitazione
                            <br>-CDR 1: Richiede aiuto per vestirsi, igiene, utilizzazione di effetti personali
                            <br>-CDR 2: Richiede molta assistenza per cura personale; non incontinenza urinaria
                            <br>-CDR 3: Richiede molta assistenza per cura personale; incontinenza urinaria
                ",
                'help_html' => true
            ])
            ->add('cdr4', null,[
                'label' => 'CDR 4',
                'help' => "Il paziente presenta severo deficit del linguaggio o della comprensione, problemi nel riconoscere i famigliari, incapacità a
                deambulare in modo autonomo, problemi ad alimentarsi da solo e nel controllare la funzione intestinale o vescicale
                ",
                'help_html' => true
            ])
            ->add('cdr5', null,[
                'label' => 'CDR 5',
                'help' => "Il paziente richiede assistenza totale perché completamente incapace di comunicare, in stato vegetativo, allettato,
                incontinente.
                ",
                'help_html' => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cdr::class,
        ]);
    }
}
