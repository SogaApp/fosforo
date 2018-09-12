<?php

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;

/**
 * Esta clase es la plantilla para definir y type de una entidad.
 * Class DefinicionEntidad
 * @package App\Form\Type
 */
abstract  class DefinicionEntidad extends AbstractType {
    /**
     * Crear un campo sin definición.
     * @return Campo
     */
    public static function campo($nombre) {
        return Campo::nuevoCampo($nombre);
    }

    /**
     * Crear un campo tipo entero.
     * @param string $label
     * @param string $tooltip
     * @return Campo
     */
    public static function entero($label, $tooltip = null) {
        return Campo::nuevoCampo($label, $tooltip)->setTipo(Campo::TIPO_NUMERO);
    }

    /**
     * Crear un campo tipo string.
     * @param string $label
     * @param string $tooltip
     * @return Campo
     */
    public static function string($label, $tooltip = null) {
        return Campo::nuevoCampo($label, $tooltip)->setTipo(Campo::TIPO_STRING);
    }

    /**
     * Crear un campo tipo fecha.
     * @param string $label
     * @param string $tooltip
     * @return Campo
     */
    public static function fecha($label, $tooltip = null) {
        return Campo::nuevoCampo($label, $tooltip)->setTipo(Campo::TIPO_DATE);
    }

    /**
     * Crear un campo tipo dateTime.
     * @param string $label
     * @param string $tooltip
     * @return Campo
     */
    public static function fechaTiempo($label, $tooltip = null) {
        return Campo::nuevoCampo($label, $tooltip)->setTipo(Campo::TIPO_DATETIME);
    }

    /**
     * Definición de función para retornar la definición de los campos.
     * @return Campo[]
     */
    public abstract static function definicionCamposLista();

    /**
     * Permite obtener los campos definidos en la entidad.
     * @return mixed
     */
    public static function getCamposLista() {
        $className = get_called_class();
        $campos = call_user_func_array([$className, 'definicionCamposLista'], []);
        foreach($campos AS $key=>$campo) {
            $campo->setNombre($key);
        }
        return $campos;
    }

}