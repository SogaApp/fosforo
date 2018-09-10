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

class PuntoEntradaController extends BaseController
{

    /**
     * @Route("movimiento/lista/{modulo}/{clase}", name="movimiento_lista_entry_point")
     */
    public function lista(Request $request, $modulo, $clase)
    {
        if(!$this->validarModulo($modulo)) {
            echo "Módulo no existe!";
            exit();
        }

        if(!$this->validarRepositorio($clase)) {
            echo "No existe el repositorio!";
            exit();
        }

        $repositorio = $this->getDoctrine()->getManager()->getRepository("App:{$this->moduloActual}\\{$this->claseActual}");
        $data = $repositorio->lista();
        var_dump($data);
        exit();
    }


}