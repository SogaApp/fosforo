<?php

namespace App\DataFixtures;

use App\Entity\RecursoHumano\Factura;
use App\Entity\RecursoHumano\FacturaTipo;
use App\Entity\RecursoHumano\FormaPago;
use App\Entity\RecursoHumano\Tercero;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class CargaInicial extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $arFormaPago = $manager->getRepository("App:RecursoHumano\FormaPago")->find(1);
        if (!$arFormaPago) {
            $arFormaPago = new FormaPago();
            $arFormaPago->setDias(30);
            $arFormaPago->setNombre("MENSUAL");
            $manager->persist($arFormaPago);
        }

        $arTercero = $manager->getRepository("App:RecursoHumano\Tercero")->find(1);
        if (!$arTercero) {
            $arTercero = new Tercero();
            $arTercero->setNit(123456789);
            $arTercero->setNombre("PRUEBA");
            $arTercero->setFormaPagoRel($arFormaPago);
            $manager->persist($arTercero);
        }

        $arFacturaTipo = $manager->getRepository("App:RecursoHumano\FacturaTipo")->find(1);
        if (!$arFacturaTipo) {
            $arFacturaTipo = new FacturaTipo();
            $arFacturaTipo->setNombre("FACTURA DE VENTA");
            $manager->persist($arFacturaTipo);
        }

        for ($i = 1; $i <= 7000; $i++) {
            $arFactura = $manager->getRepository("App:RecursoHumano\Factura")->find($i);
            if (!$arFactura) {
                $arFactura = new Factura();
                $arFactura->setTerceroRel($arTercero);
                $arFactura->setFacturaTipoRel($arFacturaTipo);
                $arFactura->setFecha(new \DateTime("now"));
                $arFactura->setNumero($i);
                $arFactura->setVrSubtotal(10000 + $i);
                $arFactura->setVrTotal(10000 + $i);
                $manager->persist($arFactura);
            }

        }
        $manager->flush();
    }
}