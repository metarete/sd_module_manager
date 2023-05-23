<?php

namespace App\Form\FormPAI;


use App\Doctrine\DBAL\Type\ISS;
use App\Doctrine\DBAL\Type\FANF;
use App\Doctrine\DBAL\Type\PANF;
use App\Doctrine\DBAL\Type\Disturbi;
use App\Doctrine\DBAL\Type\Autonomia;
use App\Doctrine\DBAL\Type\Valutazione;
use Symfony\Component\Form\AbstractType;
use App\Entity\EntityPAI\ValutazioneGenerale;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Form\SearchDiagnosiType;
use App\Entity\Diagnosi;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ValutazioneGeneraleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $Valutazione = new Valutazione();
        $valutazioneChoices = $Valutazione->getValues();
        $PANF = new PANF();
        $panfChoices = $PANF->getValues();
        $FANF = new FANF();
        $fanfChoices = $FANF->getValues();
        $ISS = new ISS();
        $issChoices = $ISS->getValues();
        $Autonomia = new Autonomia();
        $autonomiaChoices = $Autonomia->getValues();
        $Disturbi = new Disturbi();
        $disturbiChoices = $Disturbi->getValues();
        

        $builder
            
            ->add('data_valutazione', DateType::class,[
                'widget' => 'single_text', 
                'empty_data' => 0,
            ])
            ->add('tipologia_valutazione', ChoiceType::class,[
                'choices' => $valutazioneChoices,
                'placeholder' => '',
            ])
            
            ->add('n_componenti_nucleo_abitativo')
            ->add('panf', ChoiceType::class,[
                'choices' => $panfChoices,
                'placeholder' => '',
                'label' => 'Presenza Assistente Non Famigliare'
            ])
            
            ->add('fanf', ChoiceType::class,[
                'choices' => $fanfChoices,
                'placeholder' => '',
                'label' => 'Frequenza Assistente Non Famigliare'
            ])
            
            ->add('iss', ChoiceType::class,[
                'choices' => $issChoices,
                'placeholder' => '',
                'label' => 'Indicatore Supporto Sociale'
            ])
            
            ->add('buonoSociale')

            ->add('trasporti')

            ->add('voucherSociale')

            ->add('sad')

            ->add('pasti')

            ->add('assistenzaDomiciliare',CheckboxType::class, [
                'label'    => 'Assistenza Domiciliare Socio-Educativa',
                'required' => false,
            ])

            ->add('contributoCaregiver')

            ->add('uso_servizi_igenici', ChoiceType::class,[
                'choices' => $autonomiaChoices,
                'placeholder' => '',
            ])
            
            ->add('abbigliamento', ChoiceType::class,[
                'choices' => $autonomiaChoices,
                'placeholder' => '',
            ])
            
            ->add('alimentazione', ChoiceType::class,[
                'choices' => $autonomiaChoices,
                'placeholder' => '',
            ])
            
            ->add('indicatore_deambulazione', ChoiceType::class,[
                'choices' => $autonomiaChoices,
                'placeholder' => '',
            ])
            
            ->add('igene_personale', ChoiceType::class,[
                'choices' => $autonomiaChoices,
                'placeholder' => '',
            ])
            
            ->add('rischio_infettivo')
            ->add('cognitivita',ChoiceType::class,[
                'choices' => $disturbiChoices,
                'placeholder' => '',
            ])
            ->add('comportamento', ChoiceType::class,[
                'choices' => $disturbiChoices,
                'placeholder' => '',
            ])
            ->add('diagnosi', EntityType::class,[
                'class'=> Diagnosi::class,
                'choice_label' => function (Diagnosi $diagnosi) {
                    return $diagnosi->getDescrizione();
                },
                'label' => 'Diagnosi professionale',
                'help' => 'Classificazione secondo standard ICD-9-CM',
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            
            ->add('broncoaspirazione',CheckboxType::class, [
                'label'    => 'Broncoaspirazione/Drenaggio posturale',
                'required' => false,
            ])
            ->add('ossigenoTerapia')
            ->add('ventiloTerapia')
            ->add('tracheotomia')
            ->add('alimentazioneAssistita')
            ->add('alimentazioneEnterale')
            ->add('alimentazioneParenterale')
            ->add('gestioneStomia',CheckboxType::class, [
                'label'    => 'Gestione della stomia',
                'required' => false,
            ])
            ->add('eliminazioneUrina',CheckboxType::class, [
                'label'    => 'Eliminazione urinaria/intestinale',
                'required' => false,
            ])
            ->add('alterazioneSonno',CheckboxType::class, [
                'label'    => 'Alterazione del ritmo sonno/sveglia',
                'required' => false,
            ])
            ->add('educazioneTerapeutica',CheckboxType::class, [
                'label'    => 'Educazione terapeutica (addestramento/nursing)',
                'required' => false,
            ])
            ->add('ulcerePrimoSecondoGrado',CheckboxType::class, [
                'label'    => 'Ulcere da decubito di 1 e 2 grado',
                'required' => false,
            ])
            ->add('ulcereTerzoQuartoGrado',CheckboxType::class, [
                'label'    => 'Ulcere da decubito di 3 e 4 grado',
                'required' => false,
            ])
            ->add('ulcereCutaneePrimoSecondoGrado',CheckboxType::class, [
                'label'    => 'Ulcere cutanee (vascolari, traumatiche, ustioni, postchirurgiche, ecc) di 1 e 2 grado',
                'required' => false,
            ])
            ->add('ulcereCutaneeTerzoQuartoGrado',CheckboxType::class, [
                'label'    => 'Ulcere cutanee (vascolari, traumatiche, ustioni, postchirurgiche, ecc) di 3 e 4 grado',
                'required' => false,
            ])
            ->add('prelieviVenosiNonOccasionali')
            ->add('prelieviVenosiOccasionali')
            ->add('ecg')
            ->add('telemetria')
            ->add('proceduraTerapeutica',CheckboxType::class, [
                'label'    => 'Procedura Terapeutica (accesso venoso sottocute/intramuscolo)',
                'required' => false,
            ])
            ->add('gestioneCatetere',CheckboxType::class, [
                'label'    => 'Gestione catetere centrale',
                'required' => false,
            ])
            ->add('trasfusioni')
            ->add('controlloDolore',CheckboxType::class, [
                'label'    => 'Controllo del dolore',
                'required' => false,
            ])
            ->add('assistenzaOncologica',CheckboxType::class, [
                'label'    => 'Assistenza stato di terminalità oncologica',
                'required' => false,
            ])
            ->add('assistenzaNonOncologica',CheckboxType::class, [
                'label'    => 'Assistenza stato di terminalità non oncologica',
                'required' => false,
            ])
            ->add('trattamentoNeurologico',CheckboxType::class, [
                'label'    => 'Trattamento riabilitativo neurologico in presenza di disabilità',
                'required' => false,
            ])
            ->add('trattamentoOrtopedico',CheckboxType::class, [
                'label'    => 'Trattamento riabilitativo ortopedico in presenza di disabilità',
                'required' => false,
            ])
            ->add('trattamentoMantenimento',CheckboxType::class, [
                'label'    => 'Trattamento riabilitativo di mantenimento in presenza di disabilità',
                'required' => false,
            ])
            ->add('supervisioneContinua',CheckboxType::class, [
                'label'    => 'Supervisione continua di utenti con disabilità',
                'required' => false,
            ])
            ->add('assistenzaIadl',CheckboxType::class, [
                'label'    => 'Assistenza nelle IADL',
                'required' => false,
            ])
            ->add('assistenzaAdl',CheckboxType::class, [
                'label'    => 'Assistenza nelle ADL',
                'required' => false,
            ])
            ->add('supportoCaregiver',CheckboxType::class, [
                'label'    => 'Supporto al care giver',
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ValutazioneGenerale::class,
        ]);
    }
}
