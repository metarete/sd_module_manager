<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\Lesioni;
use App\Doctrine\DBAL\Type\TipoLesione;
use App\Doctrine\DBAL\Type\GradoLesione;
use App\Doctrine\DBAL\Type\Lesione;
use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\Disinfezione;
use App\Entity\BordiLesione;
use App\Entity\CondizioneLesione;
use App\Entity\Copertura;
use App\Entity\CutePerilesionale;
use App\Entity\Medicazione;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LesioniFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $Lesione = new Lesione();
        $lesioneChoices = $Lesione->getValues();
        $TipoLesione = new TipoLesione();
        $tipoLesioneChoices = $TipoLesione->getValues();
        $GradoLesione = new GradoLesione();
        $gradoLesioneChoices = $GradoLesione->getValues();
        $Disinfezione = new Disinfezione();
        $disinfezioneChoices = $Disinfezione->getValues();

        $builder
            ->add('dataRivalutazioniSettimanali', DateType::class,[
                'widget' => 'single_text',
                'empty_data' => 0,  
                'label' => 'Data di valutazione'
            ])
            ->add('lesione', ChoiceType::class,[
                'choices' => $lesioneChoices
            ])
            ->add('tipologiaLesione', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $tipoLesioneChoices
            ])
            ->add('numeroSedeLesione',null,[
                'empty_data' => 0,
            ])
            ->add('gradoLesione', ChoiceType::class,[
                'placeholder' => '',
                'choices' => $gradoLesioneChoices
            ])
            ->add('dimensioneLesione', null,[
                'label' => 'Dimensioni Lesione (cm)'
            ])
            ->add('condizioneLesione', EntityType::class,[
                'class' => CondizioneLesione::class,
                'placeholder' => '',
                'multiple'=> true,
                'autocomplete' => true,
                'choice_label' => function (CondizioneLesione $condizioneLesione) {
                    return $condizioneLesione->getNome();},
            ])
            ->add('bordiLesione', EntityType::class,[
                'class' => BordiLesione::class,
                'placeholder' => '',
                'multiple'=> true,
                'autocomplete' => true,
                'choice_label' => function (BordiLesione $bordiLesione) {
                    return $bordiLesione->getNome();},
            ])
            ->add('cutePerilesionale', EntityType::class,[
                'class' => CutePerilesionale::class,
                'placeholder' => '',
                'multiple'=> true,
                'autocomplete' => true,
                'choice_label' => function (CutePerilesionale $cutePerilesionale) {
                    return $cutePerilesionale->getNome();},
            ])
            ->add('disinfezione', ChoiceType::class ,[
                'placeholder' => '',
                'label' => 'Disinfezione',
                'choices' => $disinfezioneChoices,
            ])
            ->add('specificheDisinfezione')
            ->add('medicazione', EntityType::class,[
                'class' => Medicazione::class,
                'placeholder' => '',
                'multiple'=> true,
                'autocomplete' => true,
                'choice_label' => function (Medicazione $medicazione) {
                    return $medicazione->getNome();},
            ])
            ->add('specificheMedicazione')
            ->add('copertura', EntityType::class,[
                'class' => Copertura::class,
                'placeholder' => '',
                'multiple'=> true,
                'autocomplete' => true,
                'choice_label' => function (Copertura $copertura) {
                    return $copertura->getNome();},
            ])
            ->add('noteSullaLesione', TextareaType::class, [
                'required' => false,
                'attr' => array('style' => 'height:100px'),
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lesioni::class,
        ]);
    }
}
