<?php

namespace App\Form;

use App\Doctrine\DBAL\Type\TipoOperatoreTipiAdiweb;
use App\Entity\TipiAdiweb;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TipiAdiwebType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tipoOperatore = new TipoOperatoreTipiAdiweb();
        $tipoOperatoreChoices = $tipoOperatore->getValues();

        $builder
            ->add('nome')
            ->add('descrizione')
            ->add('codice')
            ->add('adiwebIdPrestazione')
            ->add('tipoOperatore',ChoiceType::class,[
                'placeholder' => '',
                'choices' => $tipoOperatoreChoices,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TipiAdiweb::class,
        ]);
    }
}
