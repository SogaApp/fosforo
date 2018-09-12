<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\Factura;
use App\Form\Type\DefinicionEntidad;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaType extends DefinicionEntidad
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

    public static function definicionCamposLista() {
        return [
            'numero'        => self::entero(),
            'fecha'         => self::fecha()->formato("Y/m/d"),
            'vrSubtotal'    => self::entero(),
            'vrTotal'       => self::entero(),
            'formaPago'     => self::string()->rel("tercero.formaPago.nombre"),
        ];
    }
}
