<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\Lesioni;
use App\Doctrine\DBAL\Type\TipoLesione;
use App\Doctrine\DBAL\Type\BordiLesione;
use App\Doctrine\DBAL\Type\GradoLesione;
use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\CondizioneLesione;
use App\Doctrine\DBAL\Type\CutePerilesionale;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LesioniFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $TipoLesione = new TipoLesione();
        $tipoLesioneChoices = $TipoLesione->getValues();
        $GradoLesione = new GradoLesione();
        $gradoLesioneChoices = $GradoLesione->getValues();
        $BordiLesione = new BordiLesione();
        $bordiLesioneChoices = $BordiLesione->getValues();
        $CondizioneLesione = new CondizioneLesione();
        $condizioneLesioneChoices = $CondizioneLesione->getValues();
        $CutePerilesionale = new CutePerilesionale();
        $cuteChoices = $CutePerilesionale->getValues();

        $builder
            ->add('dataRivalutazioniSettimanali', DateType::class,[
                'widget' => 'single_text',
                'empty_data' => 0,  
            ])
            ->add('tipologiaLesione', ChoiceType::class,[
                'choices' => $tipoLesioneChoices
            ])
            ->add('numeroSedeLesione',null,[
                'empty_data' => 0,
            ])
            ->add('gradoLesione', ChoiceType::class,[
                'choices' => $gradoLesioneChoices
            ])
            ->add('condizioneLesione', ChoiceType::class,[
                'choices' => $condizioneLesioneChoices
            ])
            ->add('bordiLesione', ChoiceType::class,[
                'choices' => $bordiLesioneChoices
            ])
            ->add('cutePerilesionale', ChoiceType::class,[
                'choices' => $cuteChoices
            ])
            ->add('noteSullaLesione', TextType::class, [
                'required' => false,
                'attr' => array('style' => 'height:100px')
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
