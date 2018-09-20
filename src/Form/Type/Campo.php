<?php
/**
 * Created by PhpStorm.
 * User: jako
 * Date: 10/09/18
 * Time: 09:50 PM
 */

namespace App\Form\Type;

/**
 * Esta clase representa la información de un campo de una entidad.
 * @package App\Form\Type
 */
class Campo
{
    const TIPO_STRING   = 'string';
    const TIPO_PK       = 'primary_key';
    const TIPO_NUMERO   = 'number';
    const TIPO_DATE     = 'date';
    const TIPO_DATETIME = 'datetime';
    const TIPO_MONEDA   = 'number_format';
    private $esPk       = false;
    private $tipo;
    private $nombre;
    private $maximoCaracteres;
    private $valor      = null;
    private $formatDate = 'Y-m-d';
    private $formatDateTime = 'Y-m-d H:i:ss';
    private $decimales  = '0';
    private $separadorDecimal = ',';
    private $separadorMiles = '.';
    private $esRelacion = false;
    private $relacion   = null;
    # Vamos a hacer lo de los labels y los tooltips
    private $tooltip    = null;
    private $label      = null;
    private $alineacion = null;

    public function __construct($label = "", $tooltip = "")
    {
        $this->label = $label;
        $this->tooltip = !empty($tooltip) ? $tooltip : $label; # Si no se definió tooltip asignamos el mismo label como tooltip.
    }

    /**
     * @param $relacion
     * @return Campo
     */
    public function rel($relacion)
    {
        $this->esRelacion = true;
        $this->relacion = $relacion;
        return $this;
    }

    public function esRel()
    {
        return $this->esRelacion;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    # ahora si me inspiré!
    public function setEsPk($esPk) {
        $this->esPk = $esPk;
        return $this;
    }

    /**
     * Define el tipo de dato manejado por el campo.
     * @param $tipo
     * @return $this
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Define el valor que tendrá el campo.
     * @param null $valor
     * @return $this
     */
    public function setValor($valor = null)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Define el máximo de caracteres que contendrá un campo.
     * @param $maximo
     * @return $this
     */
    public function setMaximo($maximo)
    {
        $this->maximoCaracteres = $maximo;
        return $this;
    }

    /**
     * Retorna la definición de un campo en formato array.
     * @return array
     */
    public function getDefinicion()
    {
        return [
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'max' => $this->maximoCaracteres,
            'valor' => $this->resolverValor(),
        ];
    }

    /**
     * Permite asignar un nombre al campo.
     * @param $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Retorna el nombre asiganado al campo.
     * @param $nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    public function getAlineamiento()
    {
        return $this->alineacion;
    }

    /**
     * Resuelve el valor del campo basandose en su tipo.
     * @return null
     */
    public function resolverValor()
    {
        switch ($this->tipo) {
            case self::TIPO_DATE:
                return $this->valor ? $this->valor->format($this->formatDate) : null;
            case self::TIPO_DATETIME:
                return $this->valor ? $this->valor->format($this->formatDateTime) : null;
            case self::TIPO_MONEDA:
                return $this->valor ? number_format($this->valor, $this->decimales, $this->separadorDecimal, $this->separadorMiles) : null;
            default :
                return $this->valor;
        }
    }

    /**
     * @param mixed ...$formato Recibe multiples argumentos
     * @return $this
     */
    public function formato(...$formato)
    {
        switch ($this->tipo) {
            case self::TIPO_DATE:
                $this->formatDate = $formato[0] ?? $this->formatDate;
                break;
            case self::TIPO_MONEDA:
                $this->decimales = $formato[0] ?? $this->decimales;
                $this->separadorDecimal = $formato[1] ?? $this->separadorDecimal;
                $this->separadorMiles = $formato[2] ?? $this->separadorMiles;
                break;
        }
        return $this;
    }

    /**
     * @param $alineacion
     */
    public function alineacion($alineacion)
    {
        $this->alineacion = $alineacion == "c" ? "center" : $alineacion == "r" ? "right" : "left";

        return $this;
    }

    /**
     * Permite construir un nuevo campo.
     * @param string $nombre
     * @return Campo
     */
    public static function nuevoCampo($label = "", $tooltip = null)
    {
        return new Campo($label, $tooltip);
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getTooltip()
    {
        return $this->tooltip;
    }

    public function getRel()
    {
        return $this->relacion;
    }

    public function esPk() {
        return $this->esPk;
    }
}