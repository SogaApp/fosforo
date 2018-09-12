<?php

namespace App\Repository\General;

use App\Entity\RecursoHumano\FacturaTipo;
use App\Form\Type\Campo;
use App\Form\Type\RecursoHumano\FacturaType;
use App\Repository\AbstractRepository;
use Brasa\GeneralBundle\Entity\Departamento;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use App\Util\Mensajes;

class DepartamentoRepository extends AbstractRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Departamento::class);
    }
}