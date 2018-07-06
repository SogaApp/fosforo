<?php

namespace App\Repository;

use App\Entity\Factura;
use App\Entity\FacturaDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use App\Util\Mensajes;

class FacturaDetalleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FacturaDetalle::class);
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
}