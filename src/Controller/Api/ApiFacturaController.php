<?php

namespace App\Controller\Api;

use App\Entity\Factura;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ApiFacturaController extends FOSRestController
{
    /**
     * @Rest\Get("/api/factura/lista/{codigoFactura}", name="api_factura_lista")
     */
    public function lista(Request $request, $codigoFactura)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->from(Factura::class, "f")
            ->select("f.codigoFacturaPk")
            ->where("f.codigoFacturaPk <> 0");
        return $qb->getQuery()->getResult();
    }




}
