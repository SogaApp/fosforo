<?php

namespace App\Controller;

use App\Entity\Factura;
use App\Util\General;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \SoapClient;


class InicioController extends Controller
{


    /**
     * @Route("/", name="inicio")
     */
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository(Factura::class)->miMetodo();
        return $this->redirect($this->generateUrl('saludo'));
    }

    /**
     * @Route("/saludo", name="saludo")
     */
    public function saludo() {
        return $this->render('inicio.html.twig', array(
            'number' => "saludo",
        ));
    }

}
