<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\Factura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('fecha')
            ->add('vrSubtotal')
            ->add('vrTotal')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
        ]);
    }

    public static function getCamposLista() {
        return [
            'numero' => 'Número',
            'fecha' => 'Fecha',
            'vrSubtotal' => 'Subtotal',
            'vrTotal' => 'Total',
        ];
    }
}
