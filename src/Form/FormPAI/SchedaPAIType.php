<?php

namespace App\Form\FormPAI;

use App\Entity\User;
use App\Entity\EntityPAI\SchedaPAI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SchedaPAIType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dataInizio', DateType::class,[
                'widget' => 'single_text',  
            ])
            ->add('dataFine', DateType::class,[
                'widget' => 'single_text',  
            ])
            ->add('idOperatorePrincipale', EntityType::class,[
                'class'=> User::class,
                //'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('idOperatoreSecondarioInf', EntityType::class,[
                'class'=> User::class,
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
               
            ->add('idOperatoreSecondarioTdr', EntityType::class,[
                'class'=> User::class,
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('idOperatoreSecondarioLog', EntityType::class,[
                'class'=> User::class,
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('idOperatoreSecondarioAsa', EntityType::class,[
                'class'=> User::class,
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('idOperatoreSecondarioOss', EntityType::class,[
                'class'=> User::class,
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('abilitaBarthel')
            ->add('frequenzaBarthel')
            ->add('abilitaBraden')
            ->add('frequenzaBraden')
            ->add('abilitaSpmsq')
            ->add('frequenzaSpmsq')
            ->add('abilitaTinetti')
            ->add('frequenzaTinetti')
            ->add('abilitaVas')
            ->add('frequenzaVas')
            ->add('abilitaLesioni')
            ->add('frequenzaLesioni')
            ->add('idAssistito',TextType::class, array(
                'disabled' => true,
            ))
            ->add('idConsole',TextType::class, array(
                'disabled' => true,
            ))
            ->add('idProgetto',TextType::class, array(
                'disabled' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SchedaPAI::class,
        ]);
    }
}
