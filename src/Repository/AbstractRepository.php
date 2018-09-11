<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Esta clase contiene las funciones base de un repositorio para generar listados, vistas y manejo
 * de formularios de manera dinámica.
 * Class AbstractRepository
 * @package App\Repository
 */
abstract class AbstractRepository extends ServiceEntityRepository{

    /**
     * Esta función retorna la query utilizada para listar los registros.
     * @param $repositorio
     * @param $alias
     * @param $pk
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryLista($repositorio, $alias, $pk) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from($repositorio,$alias)
            ->select("{$alias}.{$pk}");
        return $qb;
    }

    /**
     * Esta función construye la query del listado
     * @param $repositorio
     * @param array $campos
     * @param string $pk
     * @param string $alias
     * @return array
     */
    protected function procesarQueryLista($repositorio, $campos=[], $pk = "", $alias = "t") {
        $qb = $this->queryLista($repositorio, $alias, $pk);
        # Construimos los select de la consulta.
        foreach($campos AS $campo) {
            $qb->addSelect("{$alias}.{$campo}");
        }
        $data = $qb->getQuery()->getResult();
        return $this->procesarResultadosLista($campos, $data);
    }

    /**
     * Esta función toma los resultados arrojados por la consulta y resuleve los valores según el tipo de dato
     * definido en los campos a mostrar.
     * @param $campos
     * @param $data
     * @return array
     */
    protected function procesarResultadosLista($campos, $data) {
        $resultados = [];
        foreach($data AS $registro) {
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
    protected function resolverValoresCampos($campos, $data) {
        $valores = [];
        foreach($campos AS $campo) {
            $campo->setValor($data[$campo->getNombre()]?? null);
            $valores[$campo->getNombre()] = $campo->resolverValor();
        }
        return $valores;
    }
}