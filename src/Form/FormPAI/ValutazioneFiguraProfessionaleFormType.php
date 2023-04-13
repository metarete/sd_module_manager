<?php

namespace App\Form\FormPAI;

use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\TipoOperatore;
use App\Entity\Diagnosi;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use App\Entity\Obiettivi;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ValutazioneFiguraProfessionaleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $TipoOperatore = new TipoOperatore();
        $operatoreChoices = $TipoOperatore->getValues();

        $builder
            ->add('dataValutazione', DateType::class, [
                'widget' => 'single_text',
                'empty_data' => 0,
            ])
            ->add('tipoOperatore', ChoiceType::class, [
                'choices' => $operatoreChoices,
                'placeholder' => '',
            ])
            ->add('diagnosi', EntityType::class,[
                'class'=> Diagnosi::class,
                'choice_label' => function (Diagnosi $diagnosi) {
                    return $diagnosi->getDescrizione();},
                'label' => 'Diagnosi professionale',
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('obiettivi', EntityType::class,[
                'class'=> Obiettivi::class,
                'choice_label' => function (Obiettivi $obiettivi) {
                    if($obiettivi->isStato() == true)
                    return $obiettivi->getTitolo();},
                'label' => 'Obiettivi da raggiungere',
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('tipoEFrequenza', TextareaType::class, [
                'attr' => array('style' => 'height:100px'),
                'required'   => false,
                'empty_data' => '',
            ])
            ->add('modalitaTempiMonitoraggio', TextareaType::class, [
                'attr' => array('style' => 'height:100px'),
                'required'   => false,
                'empty_data' => '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ValutazioneFiguraProfessionale::class,
        ]);
    }
}
