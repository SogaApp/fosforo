<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\Factura;
use App\Form\Type\RecursoHumano\FacturaType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use App\Util\Mensajes;

class FacturaRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Factura::class);
    }

    public function miMetodo(): string
    {
        Mensajes::success("Se guardó correctamente!");
        Mensajes::error("Ocurrió un error");
        Mensajes::info("Info!!");
        Mensajes::warning("Advertencia!!!");
        echo "hola mundo";
        return "respuesta";
    }

    public function lista()
    {
        $campos = FacturaType::getCamposLista();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from("App:RecursoHumano\Factura","f")
            ->select("f.codigoFacturaPk");
        $columnas = array_keys($campos);
        foreach($columnas AS $columna) {
            $qb->addSelect("f.{$columna}");
        }
        $data = $qb->getQuery()->getResult();
        return [
            'columnas' => $campos,
            'data' => $data,
        ];
    }
}