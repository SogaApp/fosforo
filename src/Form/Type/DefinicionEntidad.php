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
     * @return Campo
     */
    public static function entero() {
        return Campo::nuevoCampo()->setTipo(Campo::TIPO_NUMERO);
    }

    /**
     * Crear un campo tipo string.
     * @return Campo
     */
    public static function string() {
        return Campo::nuevoCampo()->setTipo(Campo::TIPO_STRING);
    }

    /**
     * Crear un campo tipo fecha.
     * @return Campo
     */
    public static function fecha() {
        return Campo::nuevoCampo()->setTipo(Campo::TIPO_DATE);
    }

    /**
     * Crear un campo tipo dateTime.
     * @return Campo
     */
    public static function fechaTiempo() {
        return Campo::nuevoCampo()->setTipo(Campo::TIPO_DATETIME);
    }

    /**
     * Definición de función para retornar la definición de los campos.
     * @return Campo[]
     */
    public abstract static function definicionCampos();

    /**
     * Permite obtener los campos definidos en la entidad.
     * @return mixed
     */
    public static function getCamposLista() {
        $className = get_called_class();
        $campos = call_user_func_array([$className, 'definicionCampos'], []);
        foreach($campos AS $key=>$campo) {
            $campo->setNombre($key);
        }
        return $campos;
    }

}