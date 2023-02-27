<?php

namespace App\Form\FormPAI;

use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\TipoOperatore;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
                'choices' => $operatoreChoices
            ])
            ->add('diagnosiProfessionale', TextareaType::class, [
                'attr' => array('style' => 'height:100px'),
                'empty_data' => '',
            ])
            ->add('obbiettiviDaRaggiungere', TextareaType::class, [
                'attr' => array('style' => 'height:100px'),
                'empty_data' => '',
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
