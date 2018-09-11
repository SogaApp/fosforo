<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\Factura;
use App\Entity\RecursoHumano\FormaPago;
use App\Form\Type\Campo;
use App\Form\Type\RecursoHumano\FacturaType;
use App\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use App\Util\Mensajes;

class FormaPagoRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FormaPago::class);
    }

}