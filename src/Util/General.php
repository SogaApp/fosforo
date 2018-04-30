<?php

namespace App\Util;

final class General {
    private $container;
    private $edad = 25;

    private function __construct() 
    {
        /**
         * Lógica para inicializar utilidades...
         */
        global $kernel;
        $this->container = $kernel->getContainer();
    }

    /**
     * @return General
     */
    public static function get() 
    {
        static $instance = null;
        if(!$instance) {
            $instance = new General();
        }
        return $instance;
    }

    /**
     * Esta función te saluda.
     * @return string
     */
    public function sayHello($nombre) 
    {
        return "Hola {$nombre}";
    }

    /**
     * Esta función retorna el directorio base de la aplicación.
     * @return string
     */
    public function getRoot()
    {
        $ds = DIRECTORY_SEPARATOR;
        return realpath($this->container->get("kernel")->getRootDir() . $ds . '..');
    }

    public function setEdad($edad)
    {
        $this->edad = $edad;
    }

    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Si se tuviese configurado el security.token_storage se podría obtener el nombre del
     * usuario que ha iniciado sesión.
     * @return mixed
     */
    public function getUsername()
    {
        return $this->container->get("security.token_storage")->getToken()->getUser();
    }
}