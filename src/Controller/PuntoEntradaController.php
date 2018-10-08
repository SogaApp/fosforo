<?php
/**
 * Created by PhpStorm.
 * User: juanfelipe
 * Date: 10/09/18
 * Time: 03:31 PM
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PuntoEntradaController extends AbstractController
{

    /**
     * @Route("movimiento/lista/{modulo}/{clase}", name="movimiento_lista_entry_point")
     */
    public function lista(Request $request, $modulo, $clase)
    {
        $this->moduloActual = $modulo;
        $this->claseActual = $clase;
        $this->request = $request;

        return $this->render('/generic/lista.html.twig', [
            'data' => $this->getListaEntidad(),
            'form_filtro' => $this->getFormFiltro(),
        ]);
    }


}