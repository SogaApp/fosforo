<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FacturaController extends Controller
{
    /**
     * @Route("/factura", name="factura")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository("App:Factura")->find(1);
        $arFacturasDetalles = [];
        if ($arFactura) {
            $arFacturasDetalles = $arFactura->getFacturasDetallesFacturaRel();
        }
        return $this->render('factura/index.html.twig', [
            'controller_name' => 'FacturaController',
            'arFactura' => $arFactura,
            'arFacturasDetalles' => $arFacturasDetalles,
        ]);
    }
}
