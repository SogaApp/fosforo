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
            ->add('vrTotal');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
        ]);
    }

    public static function definicionCamposLista()
    {
        # Labels y tooltips
        # Crear función en twig
        return [
            'numero' => self::entero("Número"),
            'facturaTipo' => self::string("Tipo factura")->rel("facturaTipo.nombre"),
            'nitTercero' => self::entero("Nit")->rel("tercero.nit"),
            'tercero' => self::string("Tercero")->rel("tercero.nombre"),
            'formaPago' => self::string("Forma de pago")->rel("tercero.formaPago.nombre"),
            'fecha' => self::fecha("Fecha")->formato("Y/m/d"),
            'vrSubtotal' => self::entero("Sub total"),
            'vrTotal' => self::entero("Total"),
        ];
    }
}
