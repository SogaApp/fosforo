<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseController extends Controller {
    protected $moduloActual = null;
    protected $claseActual = null;
    /**
     * @var EntityManager
     */
    protected $em = null;

    protected $modulos = [
        'rhu' => 'RecursoHumano',
        'turno' => false,
        'inventario' => false,
    ];

    protected function validarModulo($modulo = '') {
        $existe = key_exists($modulo, $this->modulos) && $this->modulos[$modulo] !== false;
        $this->moduloActual = $existe? $this->modulos[$modulo] : null;
        return $existe;
    }

    protected function validarRepositorio($clase) {
        $nombreRepo = ucfirst($clase);
        $namespace = "\App\Repository\\{$this->moduloActual}\\{$nombreRepo}Repository";
        $existe = class_exists($namespace);
        $this->claseActual = $existe? $nombreRepo : null;
        return $existe;
    }
}