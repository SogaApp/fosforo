<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\FacturaTipo;
use App\Form\Type\Campo;
use App\Form\Type\RecursoHumano\FacturaType;
use App\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use App\Util\Mensajes;

class FacturaTipoRepository extends AbstractRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FacturaTipo::class);
    }
}