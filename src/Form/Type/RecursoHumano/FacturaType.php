<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\Factura;
use App\Form\Type\DefinicionEntidad;
use Sonata\AdminBundle\Form\Type\Filter\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    /**
     * @return \App\Form\Type\Campo[]|array
     */
    public static function definicionCamposLista()
    {
        # Labels y tooltips
        # Crear función en twig
        return [
            'codigoFacturaPk' => self::pk("id"),
            'numero' => self::entero("Número"),
            'facturaTipoRel' => self::string("Tipo factura")->rel("facturaTipoRel.nombre"),
            'nitTercero' => self::entero("Nit")->rel("terceroRel.nit"),
            'tercero' => self::string("Tercero")->rel("terceroRel.nombre"),
            'pais' => self::string("Pais")->rel("terceroRel.ciudadRel.departamentoRel.paisRel.nombre"),
            'formaPago' => self::string("Forma de pago")->rel("terceroRel.formaPagoRel.nombre"),
            'fecha' => self::fecha("Fecha")->formato("Y/m/d"),
            'vrSubtotal' => self::moneda("Sub total")->alineacion("r"),
            'vrTotal' => self::moneda("Total")->formato(2, ",", ".")->alineacion("r"),
        ];
    }

    public static function definicionCamposFiltro(FormBuilderInterface $builder)
    {
//        $this->get('form.factory')->createNamedBuilder("Factura", 'form')
        $form = $builder
            ->add('numero', NumberType::class)
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }
}
