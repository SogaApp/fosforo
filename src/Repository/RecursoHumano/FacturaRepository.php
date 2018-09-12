<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\Factura;
use App\Form\Type\Campo;
use App\Form\Type\RecursoHumano\FacturaType;
use App\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use App\Util\Mensajes;

class FacturaRepository extends AbstractRepository
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
        $this->procesarQueryLista(
            "App:RecursoHumano\Factura",
            $campos,
            "codigoFacturaPk");
//        $this->queryLista->setMaxResults(10000);
//        $data = $this->queryLista->getQuery()->getResult();
//        $resultados = $this->procesarResultadosLista($campos, $data);
//        var_dump($resultados);
//        exit();
//        $resultados = $data;
        # con el fin de hacer pruebas voy a retornar el resultado crudo, para ver si le puedo delegar toda la responsabilidad de
        # obtener los valores directamente desde twig.
        return [
            'columnas' => $campos,
//            'data' => $resultados,
            'query' => $this->queryLista,
        ];
    }
}