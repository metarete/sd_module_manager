<?php

namespace App\Form\FormPAI;

use App\Entity\User;
use App\Entity\EntityPAI\SchedaPAI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SchedaPAIType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dataInizio', DateType::class,[
                'widget' => 'single_text', 
                'disabled' => true, 
            ])
            ->add('dataFine', DateType::class,[
                'widget' => 'single_text',  
                'disabled' => true,
            ])
            ->add('idOperatorePrincipale', EntityType::class,[
                'class'=> User::class,
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();},
                'label' => 'Operatore Principale',
                'multiple'=> false,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('idOperatoreSecondarioInf', EntityType::class,[
                'class'=> User::class,
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();},
                'label' => 'Operatori Secondari Inf',
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
               
            ->add('idOperatoreSecondarioTdr', EntityType::class,[
                'class'=> User::class,
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();},
                'label' => 'Operatori Secondari Tdr',    
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('idOperatoreSecondarioLog', EntityType::class,[
                'class'=> User::class,
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();
                },
                'label' => 'Operatori Secondari Log',
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('idOperatoreSecondarioAsa', EntityType::class,[
                'class'=> User::class,
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();
                },
                'label' => 'Operatori Secondari Asa',
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('idOperatoreSecondarioOss', EntityType::class,[
                'class'=> User::class,
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();
                },
                'label' => 'Operatori Secondari Oss',
                'multiple'=> true,
                'required'   => false,
                'autocomplete' => true,
            ])
            ->add('abilitaBarthel')
            ->add('frequenzaBarthel',null,[
                'empty_data' => 0,
            ])
            ->add('abilitaBraden')
            ->add('frequenzaBraden',null,[
                'empty_data' => 0,
            ])
            ->add('abilitaSpmsq')
            ->add('frequenzaSpmsq',null,[
                'empty_data' => 0,
            ])
            ->add('abilitaTinetti')
            ->add('frequenzaTinetti',null,[
                'empty_data' => 0,
            ])
            ->add('abilitaVas')
            ->add('frequenzaVas',null,[
                'empty_data' => 0,
            ])
            ->add('abilitaLesioni')
            ->add('frequenzaLesioni',null,[
                'empty_data' => 0,
            ])
            ->add('salva', SubmitType::class, ['label' => 'Salva'])
            ->add('salvaEApprova', SubmitType::class, [
                'label' => 'Salva e approva',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SchedaPAI::class,
        ]);
    }
}
