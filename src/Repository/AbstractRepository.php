<?php

namespace App\Repository;

use App\Form\Type\Campo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Esta clase contiene las funciones base de un repositorio para generar listados, vistas y manejo
 * de formularios de manera dinámica.
 * Class AbstractRepository
 * @package App\Repository
 */
abstract class AbstractRepository extends ServiceEntityRepository
{

    protected $maxRecordsPerPage = 10000;
    protected $arrRelacionesRegistradas = [];
    /**
     * @var QueryBuilder
     */
    protected $queryLista = null;

    /**
     * Esta función retorna la query utilizada para listar los registros.
     * @param $repositorio
     * @param $alias
     * @param $pk
     */
    protected function queryLista($repositorio, $alias, $pk)
    {
        $em = $this->getEntityManager();
        $this->queryLista = $em->createQueryBuilder()->from($repositorio, $alias)
            ->select("{$alias}.{$pk}");
//        $this->queryLista->setMaxResults($this->maxRecordsPerPage);
    }

    /**
     * Esta función construye la query del listado
     * @param $repositorio
     * @param Campo[] $campos
     * @param string $pk
     * @param string $alias
     * @return array
     */
    protected function procesarQueryLista($repositorio, $campos = [], $pk = "", $alias = "t")
    {
        $this->queryLista($repositorio, $alias, $pk);
        # Construimos los select de la consulta.
        # Por ahora no lo haremos con cada uno de los campos.
        $this->queryLista->select("{$alias}");
        $relsCount = 0;

        foreach ($campos AS $campo) {
            if ($campo->esRel()) {
                $this->resolverRelaciones($campo->getRel(), $this->queryLista, $alias, $relsCount);
                $relsCount++;
            }
        }

        $this->queryLista->getQuery()->getDQL();

    }

    /**
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
            $query->leftJoin("{$alias}.{$primerAlias}Rel", $aliasRelacion);
            $query->addSelect($aliasRelacion);
            $this->arrRelacionesRegistradas[$primerAlias] = $aliasRelacion; # Registramos el alias.
        } else if (key_exists($primerAlias, $this->arrRelacionesRegistradas)) {
            $aliasRelacion = $this->arrRelacionesRegistradas[$primerAlias];
        }

        foreach ($partes AS $rel) {
            if (!key_exists($rel, $this->arrRelacionesRegistradas)) {
                $nuevoAliasRelacion = "{$rel}_{$conteo}";
                $query->leftJoin("{$aliasRelacion}.{$rel}Rel", $nuevoAliasRelacion);
                $query->addSelect($nuevoAliasRelacion);
                $this->arrRelacionesRegistradas[$rel] = $nuevoAliasRelacion; # Registramos el alias.
                $aliasRelacion = $nuevoAliasRelacion;
            } else {
                $aliasRelacion = $this->arrRelacionesRegistradas[$rel];
            }
        }
    }

    /**
     * Esta función toma los resultados arrojados por la consulta y resuleve los valores según el tipo de dato
     * definido en los campos a mostrar.
     * @param $campos
     * @param $data
     * @return array
     */
    protected function procesarResultadosLista($campos, $data)
    {
        $resultados = [];
        foreach ($data AS $registro) {
            $resultados[] = $this->resolverValoresCampos($campos, $registro);
        }
        return $resultados;
    }

    /**
     * Esta función formate los valores de los campos según el tipo definido en el Type.
     * @param $campos Campo[]
     * @param $data
     * @return []
     */
    protected function resolverValoresCampos($campos, $data)
    {
        $valores = [];
        foreach ($campos AS $campo) {
            $campo->setValor($data[$campo->getNombre()] ?? null);
            $valores[$campo->getNombre()] = $campo->resolverValor();
        }
        return $valores;
    }
}