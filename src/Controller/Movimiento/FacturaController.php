<?php

namespace App\Controller\Movimiento;

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
    public function agregarDetalle(Request $request, $codigoFactura = 0)
    {
        $arrData = json_decode($request->request->get("detalle"), true);
        # Se puede procesar la data enviada por ajax.
        return $this->getDoctrine()
            ->getManager()
            ->getRepository("App:FacturaDetalle")
            ->agregarDetalle($codigoFactura, $arrData);
    }

    /**
     * @Rest\Get("/api/factura/documento/{id}")
     */
    public function archivoMasivo($id){
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_URL => 'http://carbono.soga.com/document/pdf/download/' . $id,
            ));
            $resp = json_decode(curl_exec($curl), true);
            curl_close($curl);
            if ($resp && $resp['status'] == true) {
                $file = $resp['binary'];
                $type = $resp['type'];
                header('Content-Description: File Transfer');
                header("Content-Type: {$type}");
                header('Content-Disposition: attachment; filename=' . "file." . $type);
                header("Content-Transfer-Encoding: base64");
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . strlen($file));
                readfile($file);
                exit;
            }
            return false;
        }
        catch (\Exception $exception){
            return $exception;
        }
    }


}
