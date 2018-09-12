<?php

namespace App\Repository\General;

use App\Entity\RecursoHumano\FacturaTipo;
use App\Form\Type\Campo;
use App\Form\Type\RecursoHumano\FacturaType;
use App\Repository\AbstractRepository;
use Brasa\GeneralBundle\Entity\Ciudad;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use App\Util\Mensajes;

class CiudadRepository extends AbstractRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ciudad::class);
    }
}