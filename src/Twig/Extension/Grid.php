<?php
/**
 * Created by PhpStorm.
 * User: jako
 * Date: 11/09/18
 * Time: 08:07 PM
 */

namespace App\Twig\Extension;

use App\Form\Type\Campo;
use Doctrine\ORM\EntityManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Esta clase es la que hace la magia de dibujar la grid,
 * simplemente es una extensión de twig, "AbstractExtension", en este método "getFunctions" yo defino las funciones
 * que estarán disponibles en twig, se les pone el nombre que se desee, y en el último argumento le digo cual va a ser la
 * función que desencadena la magia "dibujarGrid".
 * Class Grid
 * @package App\Twig\Extension
 */
class Grid extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('grilla', [$this, "dibujarGrid"]),
        ];
    }

    /**
     * @param Campo[] $campos
     * @param $data []
     */
    public function dibujarGrid($campos = [], $data = [])
    {
        $cabecera = $this->dibujarEncabezado($campos); # Se llama el método para construir encabezados.
        $cuerpo = $this->dibujarCuerpo($campos, $data);
        $salidaHtml = "{$cabecera}{$cuerpo}";
        return $this->tag("table", $salidaHtml, ['border' => 1]);
    }

    public function dibujarCuerpo($campos, $data)
    {
        # Intenté ver si podía escanear la información de la entidad desde aqui. no pude XD
        $filas = [];
        foreach ($data AS $fila) {
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
    private function dibujarColumnas($campos, $entidad)
    {
        $columnas = [];
        foreach ($campos AS $campo) {
            $align = $campo->getAlineamiento(); // "center, right, left
            if (!$campo->esRel()) {
                $valor = $this->llamarMetodoGet($campo->getNombre(), $entidad);
                $campo->setValor($valor);
                $columnas[] = $this->tag("td", $campo->resolverValor(), ['align' => $align]);
            } else {
                $valor = $this->resolverRelacion($campo->getRel(), $entidad);
                $columnas[] = $this->tag("td", $valor, ['align' => $align]);
            }
        }
        return implode('', $columnas);
    }

    private function resolverRelacion($relacion, $entidad)
    {
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
        foreach ($partes as $nombre) {
            $nombreRel = "{$nombre}Rel";
            $arRelacion = $this->llamarMetodoGet($nombreRel, $arRelacion);
            if ($arRelacion === null) continue;
        }
        if ($arRelacion !== null) {
            $valor = $this->llamarMetodoGet($atributo, $arRelacion);
        }
        return $valor;
    }

    private function llamarMetodoGet($campo, $entidad)
    {
        $nombreMetodo = "get" . ucfirst($campo);
        if (method_exists($entidad, $nombreMetodo)) return call_user_func_array([$entidad, $nombreMetodo], []);
        return "";
    }

    /**
     * Esta función se encargará solo de dibujar los encabezados de la tabla.
     * @param Campo[] $campos
     */
    private function dibujarEncabezado($campos)
    {
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
    private function tag($tag, $content = '', $attrs = [])
    {
        $attrs = implode(" ", array_map(function ($attr, $value) {
            return "{$attr}=\"{$value}\"";
        }, array_keys($attrs), $attrs));
        return "<{$tag}" . ($attrs ? " {$attrs}" : "") . ">{$content}</{$tag}>";
    }
}