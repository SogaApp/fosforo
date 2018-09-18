<?php
/**
 * Created by PhpStorm.
 * User: jako
 * Date: 11/09/18
 * Time: 08:07 PM
 */

namespace App\Twig\Extension;

use App\Form\Type\Campo;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

/**
 * Esta clase es la que hace la magia de dibujar la grid,
 * simplemente es una extensión de twig, "AbstractExtension", en este método "getFunctions" yo defino las funciones
 * que estarán disponibles en twig, se les pone el nombre que se desee, y en el último argumento le digo cual va a ser la
 * función que desencadena la magia "dibujarGrid".
 * Class Grid
 * @package App\Twig\Extension
 */
class Grid extends \Twig_Extension {

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('grilla', [$this, "dibujarGrid"], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    /**
     * Esta función se encarga de emular el renderizado del procesor utilizado por KNP paginator.
     * @param SlidingPagination $pagination
     * @param array $queryParams
     * @param array $viewParams
     * @return array
     */
    public function renderProcessor(SlidingPagination $pagination, array $queryParams = array(), array $viewParams = array())
    {
        $data = $pagination->getPaginationData();
        $data['route'] = $pagination->getRoute();
        $data['query'] = array_merge($pagination->getParams(), $queryParams);
        return array_merge(
            $pagination->getPaginatorOptions(),
            $pagination->getCustomParameters(),
            $viewParams,
            $data
        );
    }

    /**
     * Esta función se encarga de dibujar la paginación de la tabla.
     * @param \Twig_Environment $env
     * @param SlidingPagination $pagination
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function renderPagination(\Twig_Environment $env, SlidingPagination $pagination) {
        $span = $this->tag("span",$pagination->getTotalItemCount(), ['class' => 'badge']);
        $div1 = $this->tag("div", "Registros " . $span, ['class' => "btn btn-default btn-sm"]);
        $contador = $this->tag("div", $div1, ['class' => 'btn-group', 'style' => 'float: left;margin-left: 0px;padding-top: 25px;']);
        $paginacion = $env->render(
            $pagination->getTemplate(),
            $this->renderProcessor($pagination)
        );
        $htmlPaginacion = $this->tag('div', $paginacion, ['class' => 'btn-group btn-sm', 'style' => 'float: left']);
        return $this->tag("div", $contador . $htmlPaginacion);
    }

    /**
     * @param Campo[] $campos
     * @param $data[]
     */
    public function dibujarGrid(\Twig_Environment $env, $campos=[], $data=[]) {
        $cabecera = $this->dibujarEncabezado($campos); # Se llama el método para construir encabezados.
        $cuerpo = $this->dibujarCuerpo($campos, $data);
        $paginacion = $this->renderPagination($env, $data);
        $salidaHtml = "{$cabecera}{$cuerpo}";
        return $this->tag("table", $salidaHtml, ['border' => 1]) . $paginacion;
    }

    public function dibujarCuerpo($campos, $data) {
        # Intenté ver si podía escanear la información de la entidad desde aqui. no pude XD
        $filas = [];
        foreach($data AS $fila) {
            $columnas = $this->dibujarColumnas($campos, $fila);
            $filas [] = $this->tag("tr", $columnas);
        }
        return implode('', $filas);
    }

    /**
     * @param Campo[] $campos
     * @param $entidad
     * @return string
     */
    private function dibujarColumnas($campos, $entidad) {
        $columnas = [];
        foreach($campos AS $campo) {
            if(!$campo->esRel()) {
                $valor = $this->llamarMetodoGet($campo->getNombre(), $entidad);
                $campo->setValor($valor);
                $columnas[] = $this->tag("td", $campo->resolverValor());
            } else {
                $valor = $this->resolverRelacion($campo->getRel(), $entidad);
                $columnas[] = $this->tag("td", $valor);
            }
        }
        return implode('', $columnas);
    }

    /**
     * Esta función resuelve las relaciones de un campo.
     * @param $relacion
     * @param $entidad
     * @return mixed|null|string
     */
    private function resolverRelacion($relacion, $entidad) {
        # Paso 1: Determinar cual es la relación y cual es el campo que se quiere, para ello exploto el string de configuración.
        # "tercero.formaPago.nombre"
        $partes = explode('.', $relacion);
        $ultimoIndice = count($partes) - 1;
        $atributo = $partes[$ultimoIndice];
        # removemos la última parte del string
        unset($partes[$ultimoIndice]);
        $valor = null;
        # empezamos a resolver relaciones recursivamente.
        $arRelacion = $entidad;
        foreach($partes as $nombre) {
            $nombreRel = "{$nombre}Rel";
            $arRelacion = $this->llamarMetodoGet($nombreRel, $arRelacion);
            if($arRelacion === null) continue;
        }
        if($arRelacion !== null) {
            $valor = $this->llamarMetodoGet($atributo, $arRelacion);
        }
        return $valor;
    }

    private function llamarMetodoGet($campo, $entidad) {
        $nombreMetodo = "get" . ucfirst($campo);
        if(method_exists($entidad, $nombreMetodo)) return call_user_func_array([$entidad, $nombreMetodo], []);
        return "";
    }

    /**
     * Esta función se encargará solo de dibujar los encabezados de la tabla.
     * @param Campo[] $campos
     */
    private function dibujarEncabezado($campos) {
        # Introduzco el html de cada una de las celdas de la cabecera dentro de un array y luego lo implociono para formar
        # un string con el html de la celda, es una tecnica de concatenación recomendada por ser más optima.
        $celdas = [];
        foreach ($campos AS $campo) {
            $celdas[] = $this->tag("th", $campo->getLabel(), ['title' => $campo->getTooltip()]);
        }
        $fila = $this->tag("tr", implode('', $celdas));
        return $this->tag("thead", $fila);
    }

    /**
     * La función tag lo único que hace es permitirnos generar código html sin tener que escribirlo, ya que visualmente
     * es poco entendible si se incrusta el html dentro del código PHP, esta función nos permite escribir esas etiquetas
     * sin manchar el código (Sin mencionar que es una pésima práctica incrustar HTML dentro de PHP).
     * @param $tag
     * @param string $content
     * @param array $attrs
     * @return string
     */
    private function tag($tag, $content = '', $attrs = []) {
        $attrs = implode(" ", array_map(function($attr, $value){ return "{$attr}=\"{$value}\""; }, array_keys($attrs), $attrs));
        return "<{$tag}" . ($attrs? " {$attrs}" : "") . ">{$content}</{$tag}>";
    }
}