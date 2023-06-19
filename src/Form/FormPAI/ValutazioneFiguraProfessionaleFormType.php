<?php

namespace App\Form\FormPAI;

use App\Doctrine\DBAL\Type\FrequenzaValutazioneProfessionale;
use Symfony\Component\Form\AbstractType;
use App\Doctrine\DBAL\Type\TipoOperatore;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use App\Entity\Obiettivi;
use App\Entity\TipiAdiweb;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\FormPAI\SearchDiagnosiType;

class ValutazioneFiguraProfessionaleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $TipoOperatore = new TipoOperatore();
        $operatoreChoices = $TipoOperatore->getValues();
        $Frequenza = new FrequenzaValutazioneProfessionale();
        $frequenzaChoices = $Frequenza->getValues();

        $builder
            ->add('dataValutazione', DateType::class, [
                'widget' => 'single_text',
                'empty_data' => 0,
            ])
            ->add('tipoOperatore', ChoiceType::class, [
                'choices' => $operatoreChoices,
                'placeholder' => '',
            ])
            ->add('diagnosi', SearchDiagnosiType::class)
            
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
            ->add('tipiAdiwebs', EntityType::class,[
                'class' => TipiAdiweb::class,
                'choice_label' => function (TipiAdiweb $tipiAdiweb) {
                    return $tipiAdiweb->getNome();
                },
                'label' => 'Tipo Adiweb',
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('frequenza', ChoiceType::class, [
                'choices' => $frequenzaChoices,
                'required'   => false,
                'placeholder' => '',
            ])
            ->add('osservazioni', TextareaType::class, [
                'attr' => array('style' => 'height:100px'),
                'required'   => false,
                'empty_data' => '',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ValutazioneFiguraProfessionale::class,
        ]);
    }
}
