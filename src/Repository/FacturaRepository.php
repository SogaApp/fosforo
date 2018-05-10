<?php

namespace App\Repository;

use App\Entity\Factura;
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
}