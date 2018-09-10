<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\Factura;
use App\Entity\RecursoHumano\FacturaDetalle;
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

    /**
     * @param $codigoFactura
     * @param array $data
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function agregarDetalle($codigoFactura, $data=[]) {
        $arDetalle = $this->validarAgregado($codigoFactura, $data['item']?? 0);
        if(!$data  && count($data) === 0) {
            return [
                'error' => true,
                'mensaje' => 'No se enviaron parametros para insertar el detalle',
            ];
        } else if($arDetalle !== null) {
            #Nota: Se extraen los campos uno por uno de la entidad, para enviar a la vista solo
            return [
                'error' => true,
                'yaAgregado' => true,
                'mensaje' => 'Este item ya fue agregado a la factura.',
                'detalle' => $arDetalle,
            ];
        }
        $em = $this->getEntityManager();
        # Para evitar consultar la factura completamente se trae una referencia a la factura.
        $arFactura = $em->getReference("App:Factura", $codigoFactura);
        # ToDo: Se debe tener en cuenta que el item debe ser una entidad a parte para setear campos como valor y demás.
        $arDetalle = (new FacturaDetalle())
                    ->setFacturaRel($arFactura)
                    ->setCantidad($data['cantidad']?? 0)
                    ->setItem($data['item']?? 0)
                    ->setVrPrecio($data['precio']?? 0)
                    ->setVrSubtotal($data['subtotal']?? 0)
                    ->setVrTotal($data['total']?? 0);
        $em->persist($arDetalle);
        $em->flush($arDetalle);
        return [
            'error' => false,
            'mensaje' => 'Detalle añadido!',
            'detalle' => [
                'codigo' => $arDetalle->getCodigoFacturaDetallePk(),
                'item' => $arDetalle->getItem(),
                'precio' => 0,
                'subtotal' => 0,
                'total' => 0,
                'cantidad' => 0,
            ],
        ];
    }

    /**
     * @param $codigoFactura
     * @param $codigoItem
     * @return FacturaDetalle|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function validarAgregado($codigoFactura, $codigoItem) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->from('App:FacturaDetalle', "fd")
            ->select("fd.codigoFacturaDetallePk AS codigo")
            ->addSelect("fd.item")
            ->addSelect("fd.cantidad")
            ->addSelect("fd.vrPrecio AS precio")
            ->addSelect("fd.vrSubtotal AS subtotal")
            ->addSelect("fd.vrTotal AS total")
            ->where("fd.codigoFacturaFk = {$codigoFactura}")
            ->andWhere("fd.item = {$codigoItem}")
            ->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }
}