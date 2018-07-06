<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FacturaController extends Controller
{
    /**
     * @Route("/factura/detalle/{codigoFactura}", name="factura")
     */
    public function index(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository("App:Factura")->find($codigoFactura);
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

    /**
     * @param Request $request
     * @param int $codigoFactura
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/api/factura/detalle/agregar/{codigoFactura}")
     */
    public function agregarDetalle(Request $request, $codigoFactura=0) {
        $arrData = json_decode($request->request->get("detalle"), true);
        # Se puede procesar la data enviada por ajax.
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository("App:FacturaDetalle")
                    ->agregarDetalle($codigoFactura, $arrData);
    }
}
