<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('name', null, [
                'label' => 'Nome'
            ])
            ->add('surname', null, [
                'label' => 'Cognome'
            ])
            ->add('email', null, [
                'label' => 'Email'
            ])
            ->add('username', null, [
                'label' => 'Username'
            ])
            ->add('cf', null, [
                'label' => 'Codice Fiscale'
            ])
            ->add('roles', CollectionType::class,[
                'label' => 'Ruolo'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}