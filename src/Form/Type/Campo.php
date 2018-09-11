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
class Campo {
    const TIPO_STRING   = 'string';
    const TIPO_NUMERO   = 'number';
    const TIPO_DATE     = 'date';
    const TIPO_DATETIME     = 'date';
    private $tipo;
    private $nombre;
    private $maximoCaracteres;
    private $valor = null;
    private $formatDate = 'Y-m-d';
    private $formatDateTime = 'Y-m-d H:i:ss';

    public function __construct($nombre = "") {
        $this->nombre = $nombre;
    }

    public function __toString() {
        return $this->nombre;
    }

    /**
     * Define el tipo de dato manejado por el campo.
     * @param $tipo
     * @return $this
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Define el valor que tendrá el campo.
     * @param null $valor
     * @return $this
     */
    public function setValor($valor = null) {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Define el máximo de caracteres que contendrá un campo.
     * @param $maximo
     * @return $this
     */
    public function setMaximo($maximo) {
        $this->maximoCaracteres = $maximo;
        return $this;
    }

    /**
     * Retorna la definición de un campo en formato array.
     * @return array
     */
    public function getDefinicion() {
        return [
            'nombre' => $this->nombre,
            'tipo'   => $this->tipo,
            'max'    => $this->maximoCaracteres,
            'valor'  => $this->resolverValor(),
        ];
    }

    /**
     * Permite asignar un nombre al campo.
     * @param $nombre
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    /**
     * Retorna el nombre asiganado al campo.
     * @param $nombre
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Resuelve el valor del campo basandose en su tipo.
     * @return null
     */
    public function resolverValor() {
        switch ($this->tipo) {
            case self::TIPO_DATE: return $this->valor? $this->valor->format($this->formatDate) : null;
            case self::TIPO_DATETIME: return $this->valor? $this->valor->format($this->formatDateTime) : null;
            default : return $this->valor;
        }
    }

    /**
     * Permite construir un nuevo campo.
     * @param string $nombre
     * @return Campo
     */
    public static function nuevoCampo($nombre = "") {
        return new Campo($nombre);
    }
}