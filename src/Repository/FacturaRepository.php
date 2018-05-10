<?php

namespace App\Repository;

use App\Entity\Factura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class FacturaRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Factura::class);
    }

    public function miMetodo(): string
    {
        echo "hola mundo";
        return "respuesta";
    }
}