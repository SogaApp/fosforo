<?php
/**
 * Created by PhpStorm.
 * User: juanfelipe
 * Date: 10/09/18
 * Time: 03:31 PM
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PuntoEntradaController extends AbstractController
{

    /**
     * @param Request $request
     * @param $modulo
     * @param $clase
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("movimiento/lista/{modulo}/{clase}", name="movimiento_lista_entry_point")
     */
    public function lista(Request $request, $modulo, $clase)
    {
        $this->moduloActual = $modulo;
        $this->claseActual = $clase;
        $this->request = $request;

        $form = $this->getFormFiltro();//Se construyen los filtros para mostrar en la lista según la configuración del formType
        $this->filtrar($form);//Función que ejecuta la acción del filtro
        $data = $this->getListaEntidad();//Se construyen los registro para mostar en la lista según la configuración del formType

        return $this->render('/generic/lista.html.twig', [
            'data' => $data,
            'form_filtro' => $form->createView(),
        ]);
    }


}