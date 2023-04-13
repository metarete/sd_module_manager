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
            ->add('dataValutazione', DateType::class, [
                'widget' => 'single_text',
                'empty_data' => 0,
            ])
            ->add('alimentazione', ChoiceType::class, [
                'choices' => $votiBarthel010Choices,
                'placeholder' => '',
                'help' => " 
                            -10: Capace di alimentarsi da solo quando i cibi sono preparati su un vassoio o tavolo raggiungibili.
                            Se usa un ausilio deve essere capace di utilizzarlo, tagliare i cibi e, se lo desidera, usare sale e pepe, spalmare il burro, ecc.
                            <br>-8: Indipendente nell'alimentarsi con i cibi preparati su un vassoio, ad eccezione di tagliare la carne, aprire
                            il contenitore del latte, girare il coperchio di un vasetto, ecc. Non è necessaria la presenza di un'altra persona.
                            <br>-5: Capace di alimentarsi da solo, con supervisione. Richiede assistenza nelle attività associate come versare il 
                            latte, zucchero o altro nella tazza, usare sale e pepe, spalmare il burro, girare un piatto di portata o altro.
                            <br>-2: Capace di utilizzare una posata, in genere un cucchiaio, ma qualcuno deve assistere attivamente durante il pasto
                            <br>-0: Dipendente per tutti gli aspetti. Deve essere alimentato (imboccato, SNG, PEG, ecc).
                            ",
                'help_html' => true
            ])
            ->add('bagnoDoccia', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel05Choices,
                'help' => "
                            -5: Capace di fare il bagno in vasca, la doccia o una spugnatura completa. Autonomo in tutte le operazioni, senza la
                            presenza di un'altra persona, quale che sia il metodo usato.
                            <br>-4: Necessità di supervisione per sicurezza (trasferimenti, temperatura dell'acqua, ecc).
                            <br>-3: Necessita di aiuto per il trasferimento nella doccia/bagno oppure nel lavarsi o asciugarsi.
                            <br>-1: Necessita di aiuto per tutte le operazioni.
                            <br>-0: Totale dipendenza nel lavarsi.
                            ",
                'help_html' => true

            ])
            ->add('igienePersonale', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel05Choices,
                'help' => "-5:
                            Capace di lavarsi mani e faccia, pettinarsi, lavarsi i denti e radersi. Un uomo deve essere capace di usare,
                            senza aiuto, qualsiasi tipo di rasoio, comprese le manipolazioni necessarie. Una donna deve essere in grado di truccarsi, se 
                            abituata a farlo, ma non è necessario che sia in grado di acconciarsi i capelli.
                            <br>-4:
                            In grado di attendere all'igiene personale, ma necessita di aiuto minimo prima e/o dopo le operazioni.
                            <br>-3:
                            Necessita di aiuto per una o più operazioni dell'igiene personale.
                            <br>-1:
                            Necessita di aiuto per tutte le operazioni.
                            <br>-0:
                            Incapace di attendere all'igiene personale, dipendente sotto tutti gli aspetti.
                            ",
                'help_html' => true

            ])
            ->add('abbigliamento', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel010Choices,
                'help' => "-10:
                            Capace di indossare, togliere e chiudere correttamente gli indumenti, allacciarsi le scarpe e toglierle, 
                            applicare oppure togliere un corsetto o una protesi.
                            <br>-8:
                            Necessita solo di un minimo aiuto per alcuni aspetti, come bottoni, cerniere, reggiseno, lacci di scarpe.
                            <br>-5:
                            Necessita di aiuto per mettere o togliere qualsiasi indumento.
                            <br>-2:
                            Capace di collaborare in qualche modo, ma dipendente sotto tutti gli aspetti.
                            <br>-0:
                            Dipendente sotto tutti gli aspetti e non collabora.
                            ",
                'help_html' => true
            ])
            ->add('continenzaIntestinale', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel010Choices,
                'help' => "-10:
                            Controllo intestinale completo e nessuna perdita, capace di mettersi supposte o praticarsi un 
                            enteroclisma se necessario.
                            <br>-8:
                            Può necessitare di supervisione per l'uso di supposte o enteroclisma, occasionali perdite.
                            <br>-5:
                            Capace di assumere una posizione appropriata, ma non di eseguire manovre facilitatorie o pulirsi da solo
                            senza assistenza, e ha perdite frequenti. Necessita di aiuto nell'uso di dispositivi come pannolini, ecc.
                            <br>-2:
                            Necessita di aiuto nell'assumere una posizione appropriata e necessita di manovre facilitatorie.
                            <br>-0:
                            Incontinente.
                            ",
                'help_html' => true
            ])
            ->add('continenzaUrinaria', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel010Choices,
                'help' => "-10:
                            Controllo completo durante il giorno e la notte e/o indipendente con dispositivi esterni o interni.
                            <br>-8:
                            Generalmente asciutto durante il giorno e la notte, ha occasionalmente qualche perdita e necessita di 
                            minimo aiuto per l'uso dei dispositivi esterni o interni.
                            <br>-5:
                            In genere asciutto durante il giorno ma non di notte, necessario l'aiuto parziale nell'uso dei dispositivi.
                            <br>-2:
                            Incontinente ma in grado di cooperare nell'applicazione di un dispositivo esterno o interno.
                            <br>-0:
                            Incontinente o catetere a dimora o dipendente per l'applicazione di dispositivi esterni o interni.
                            ",
                'help_html' => true
            ])
            ->add('toilet', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel010Choices,
                'help' => "-10:
                            Capace di trasferirsi sul e dal gabinetto, gestire i vestiti senza sporcarsi, usare la carta igienica
                            senza aiuto. Se necessario, può usare la comoda o padella, o il pappagallo, ma deve essere in grado di svuotarli e pulirli.
                            <br>-8:
                            Necessita di supervisione per sicurezza con l'uso del normale gabinetto. Usa la comoda indipendentemente
                            tranne che per svuotarla e pulirla.
                            <br>-5:
                            Necessita di aiuto per svestirsi/vestirsi, per i trasferimenti e per lavare le mani.
                            <br>-2:
                            Necessita aiuto per tutti gli aspetti.
                            <br>-0:
                            Completamente dipendente.
                            ",
                'help_html' => true
            ])
            ->add('trasferimentoLettoSedia', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel015Choices,
                'help' => "-15:
                            E' indipendente durante tutte le fasi. Capace di avvicinarsi al letto in carrozzina con sicurezza,
                            bloccare i freni, sollevare le pedane, trasferirsi con sicurezza sul letto, sdraiarsi, rimettersi seduto sul bordo, cambiare
                            la posizione della carrozzina.
                            <br>-12:
                            Necessita la presenza di una persona per maggiore fiducia o per supervisione a scopo di sicurezza.
                            <br>-8:
                            Necessario minimo aiuto da parte di una persona per uno o più aspetti del trasferimento.
                            <br>-3:
                            Collabora, ma richiede massimo aiuto da parte di una persona durante tutti i movimenti del trasferimento.
                            <br>-0:
                            Non collabora al trasferimento. Necessarie due persone per trasferire la persona con o senza un sollevatore meccanico.
                            ",
                'help_html' => true
            ])
            ->add('scale', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel010Choices,
                'help' => "-10:
                            In grado di salire o scendere una rampa di scale con sicurezza senza aiuto o supervisione. In 
                            grado di usare uno scorrimano, bastone o stampelle se necessario ed in grado di portarli con sè durante la salita o la discesa.
                            <br>-8:
                            In genere non richiede assistenza. Occasionalmente necessita di supervisione, per sicurezza o a causa di 
                            rigidità mattutina, dispenea, ecc.
                            <br>-5:
                            Capace di salire/scendere le scale ma non in grado di gestire gli ausili e necessita di supervisione e assistenza
                            <br>-2:
                            Necessita di aiuto per salire o scendere le scale (compreso eventualmente l'uso di ausili).
                            <br>-0:
                            Incapace di salire o scendere le scale.
                            ",
                'help_html' => true
            ])
            
            ->add('deambulazioneValida', ChoiceType::class, [
                'placeholder' => '',
                'choices' => $votiBarthel015Choices,
                'help' => "-15:
                            Capace di portare una protesi se necessario, bloccarla, sbloccarla, assumere la stazione eretta, sedersi, e 
                            piazzare gli ausili a portata di mano. In grado di usare stampelle, bastone ewalker e di deambulare per 50 metri senza supervisione.
                            <br>-12:
                            Indipendente nella deambulazione non con autonomia (50 metri). Necessita di supervisione per maggiore
                            fiducia o sicurezza in situazioni pericolose.
                            <br>-8:
                            Necessita di assistenza da una persona per raggiungere gli ausili e/o per manipolazione degli stessi.
                            <br>-3:
                            Necessita della presenza costante di uno o più assistenti durante la deambulazione.
                            <br>-0:
                            Non in grado di deambulare autonomamente.
                            ",
                'help_html' => true
            ])
            ->add('usoCarrozzina', ChoiceType::class, [
                'choices' => $votiBarthel05Choices,
                'help' => "-5:
                            Capace di compiere autonomamente tutti gli spostamenti (girare attorno agli angoli, rigirarsi, 
                            avvicinarsi al tavolo, letto, wc ecc); l'autonomia deve essere > 50m.
                            <br>-4:
                            Capace di spostarsi autonomamente, per periodi ragionevolmente lunghi, su terreni e superfici 
                            regolari. Può essere necessaria assistenza per fare curve strette.
                            <br>-3:
                            Necessita la presenza e l'assistenza costante di una persona per avvicinare la carrozzina al tavolo,
                            al letto, ecc.
                            <br>-1:
                            Capace di spostarsi solo per brevi tratti e su superfici piane, necessaria assistenza per tutte le manovre.
                            <br>-0:
                            Dipendente negli spostamenti con la carrozzina.
                            ",
                'help_html' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Barthel::class,
        ]);
    }
}
