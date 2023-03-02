<?php

namespace App\Form\FormPAI;

use App\Entity\EntityPAI\SPMSQ;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SPMSQFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dataValutazione', DateType::class,[
                'widget' => 'single_text',  
                'empty_data' => 0,
            ])
            ->add('giornoOggi', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Che giorno è oggi?',
                'required' => false,
            ])
            ->add('giornoSettimana', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Che giorno della settimana è?',
                'required' => false,
            ])
            ->add('posto', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Come si chiama questo posto?',
                'required' => false,
            ])
            ->add('indirizzo', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Qual è il suo indirizzo?',
                'required' => false,
            ])
            ->add('anni', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Quanti anni ha?',
                'required' => false,
            ])
            ->add('dataNascita', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Quando è nato?',
                'required' => false,
            ])
            ->add('presidenteAttuale', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Chi è il Presidente della Repubblica?',
                'required' => false,
            ])
            ->add('presidentePrecedente', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Chi era il Presidente precedente?',
                'required' => false,
            ])
            ->add('cognomeMadre', CheckboxType::class, [
                'label'    => 'Errori alla domanda: Qual è il cognome da ragazza si sua madre?',
                'required' => false,
            ])
            ->add('sottrazione', CheckboxType::class, [
                'label'    => 'Errori nel calcolo: Sottragga da 20 tre e poi ancora fino in fondo',
                'required' => false,
            ])
            
            ->add('salva', SubmitType::class, ['label' => 'Salva'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SPMSQ::class,
        ]);
    }
}
