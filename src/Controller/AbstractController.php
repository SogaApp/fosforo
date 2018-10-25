<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends Controller
{
    protected $maxRecordsPerPage = 10000;
    protected $arrRelacionesRegistradas = [];
    protected $filtros = [];
    protected $tableAlias = "t";
    protected $arrCondicionType = [1 => ">=", 2 => ">", 3 => "=", 4 => "<=", 5 => "<"];
    /**
     * @var QueryBuilder
     */
    protected $queryLista = null;

    protected $moduloActual = null;
    protected $claseActual = null;
    /**
     * @var EntityManager
     */
    protected $em = null;
    /**
     * @var Request
     */
    protected $request = null;

    protected $modulos = [
        'rhu' => 'RecursoHumano',
        'turno' => false,
        'inventario' => false,
    ];

    /**
     * Esta función se encarga de validar la existencia de un módulo (Los módulos se encuentran registrados en $this->modulos).
     * @param string $modulo
     * @return bool
     */
    protected function validarModulo($modulo = '')
    {
        $existe = key_exists($modulo, $this->modulos) && $this->modulos[$modulo] !== false;
        $this->moduloActual = $existe ? $this->modulos[$modulo] : null;
        return $existe;
    }

    /**
     * Esta función valida la existencia de un repositorio (Dado por la url "/[path-to-controller]/[accion]/[modulo]/[clase]"
     * @param $clase
     * @return bool
     */
    protected function validarRepositorio($clase)
    {
        $nombreRepo = ucfirst($clase);
        $namespace = "\App\Repository\\{$this->moduloActual}\\{$nombreRepo}Repository";
        $existe = class_exists($namespace);
        $this->claseActual = $existe ? $nombreRepo : null;
        return $existe;
    }

    /**
     * Esta función inicializa la consulta para litar registros de una entidad.
     * @param $repositorio
     * @param $alias
     */
    protected function getQueryLista($repositorio, $alias)
    {
        global $kernel;
        $em = $kernel->getContainer()->get("doctrine.orm.entity_manager");
//        $em = $this->getDoctrine()->getManager(); # El manager del controller no puede crear queries :/ por eso toca usar ese otro entityManager.
        $this->queryLista = $em->createQueryBuilder()->from($repositorio, $alias)
            ->select("{$alias}");
    }

    /**
     * Esta función se encarga de construir los campos a seleccionar directamente de la entidad a procesar
     * Nota: La entidad es determinada por la url en el parámetro $clase.
     * @param $repositorio
     * @param array $campos
     * @param string $alias
     */
    protected function procesarQueryLista($repositorio, $campos = [], $alias = "t")
    {
        $this->tableAlias = $alias;
        $this->getQueryLista($repositorio, $alias);
        # Construimos los select de la consulta.
        # Por ahora no lo haremos con cada uno de los campos.
//        $this->queryLista->select("{$alias}");
        $relsCount = 0;

        foreach ($campos AS $campo) {
            if ($campo->esRel()) {
                $this->resolverRelaciones($campo->getRel(), $this->queryLista, $alias, $relsCount);
                $relsCount++;
            }
        }
        $this->procesarFiltros();
        $this->queryLista->getQuery()->getDQL();
    }

    /**
     * @param $query QueryBuilder
     */
    protected function procesarFiltros()
    {
        foreach ($this->filtros as $nombreCampo => $campo) {
            $condicion = "=";
            if ($campo instanceof \DateTime) {
                $valor = $campo->format("Y-m-d H:i:s");
                $condicion = ">=";
            } else if (is_array($campo)) {
                $valor = $campo['value'] ?? null;
                if ($valor instanceof \DateTime) {
                    $valor = $campo['value']->format("Y-m-d H:i:s");
                }
                $condicion = $campo['type'] != null ? $this->arrCondicionType[$campo['type']] : $condicion;
            } else if (is_object($campo)) {

            } else {
                $valor = $campo;
            }
            if (empty($valor)) continue;
            $this->queryLista->andWhere("{$this->tableAlias}.{$nombreCampo} {$condicion} '{$valor}'");
        }
    }

    /**
     * Esta función se encarga de resolver las relaciones configuradas desde el type de la entidad.
     * @param $relacion
     * @param $query QueryBuilder
     */
    private function resolverRelaciones($relacion, &$query, $alias, $conteo = 0)
    {
        $partes = explode('.', $relacion);
        $ultimoIndice = count($partes) - 1;
        unset($partes[$ultimoIndice]); # el atributo no lo necesitamos.
        $primerAlias = $partes[0];
        unset($partes[0]);

        if (!key_exists($primerAlias, $this->arrRelacionesRegistradas)) { # No existe la relación.
            $aliasRelacion = "{$primerAlias}_{$conteo}";
            $query->leftJoin("{$alias}.{$primerAlias}", $aliasRelacion);
            $query->addSelect($aliasRelacion);
            $this->arrRelacionesRegistradas[$primerAlias] = $aliasRelacion; # Registramos el alias.
        } else if (key_exists($primerAlias, $this->arrRelacionesRegistradas)) {
            $aliasRelacion = $this->arrRelacionesRegistradas[$primerAlias];
        }

        foreach ($partes AS $rel) {
            if (!key_exists($rel, $this->arrRelacionesRegistradas)) {
                $nuevoAliasRelacion = "{$rel}_{$conteo}";
                $query->leftJoin("{$aliasRelacion}.{$rel}", $nuevoAliasRelacion);
                $query->addSelect($nuevoAliasRelacion);
                $this->arrRelacionesRegistradas[$rel] = $nuevoAliasRelacion; # Registramos el alias.
                $aliasRelacion = $nuevoAliasRelacion;
            } else {
                $aliasRelacion = $this->arrRelacionesRegistradas[$rel];
            }
        }
    }

    /**
     * @param null $filtros
     * @return array
     * @throws \Exception
     */
    protected function getListaEntidad($filtros = null)
    {
        # Si no hubo error se procede a instanciar el repositorio
        $nombreRepositorio = "App:{$this->moduloActual}\\{$this->claseActual}";
        $this->getQueryLista($nombreRepositorio, "t");
        $namespaceType = "\\App\\Form\\Type\\{$this->moduloActual}\\{$this->claseActual}Type";
        $campos = $namespaceType::getCamposLista();
        $this->procesarQueryLista($nombreRepositorio, $campos, "t");
        $paginator = $this->get('knp_paginator');
        $query = $this->queryLista->getQuery();

        return [
            'campos' => $campos,
            'datos' => $paginator->paginate($query, $this->request->query->getInt('page', 1), 30)
        ];
    }

    /**
     * @param null $modulo
     * @param null $clase
     * @return mixed
     * @throws \Exception
     */
    protected function getFormFiltro($modulo = null, $clase = null)
    {
        if (!$this->validarModulo($modulo ?? $this->moduloActual) || !$this->validarRepositorio($clase ?? $this->claseActual)) {
            throw new \Exception("Módulo o clase invalidos");
        }
        $namespaceType = "\\App\\Form\\Type\\{$this->moduloActual}\\{$this->claseActual}Type";
        return $namespaceType::definicionCamposFiltro($this->get("form.factory"));
    }

    /**
     * @param $form FormInterface
     * @throws \Exception
     */
    protected function filtrar($form)
    {
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->filtros = $form->getData();
//            foreach ($arDataForm as $nombreCampo=>$campo) {
//                $this->filtros
//            }
//            $this->getListaEntidad($arDataForm);
//            var_dump($arDataForm);
//            exit();
        }
    }

}