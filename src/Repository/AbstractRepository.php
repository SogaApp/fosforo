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
abstract class AbstractRepository extends ServiceEntityRepository{

    protected $maxRecordsPerPage = 100;
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
    protected function queryLista($repositorio, $alias, $pk) {
        $em = $this->getEntityManager();
        $this->queryLista = $em->createQueryBuilder()->from($repositorio,$alias)
            ->select("{$alias}.{$pk}");
        $this->queryLista->setMaxResults($this->maxRecordsPerPage);
    }

    /**
     * Esta función construye la query del listado
     * @param $repositorio
     * @param Campo[] $campos
     * @param string $pk
     * @param string $alias
     * @return array
     */
    protected function procesarQueryLista($repositorio, $campos=[], $pk = "", $alias = "t") {
        $this->queryLista($repositorio, $alias, $pk);
        # Construimos los select de la consulta.
        foreach($campos AS $campo) {
            if(!$campo->esRel()) {
                $this->queryLista->addSelect("{$alias}.{$campo}");
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