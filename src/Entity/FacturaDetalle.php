<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FacturaDetalleRepository")
 */
class FacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoFacturaDetallePk;

    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer")
     */
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="cantidad", type="float", nullable=true)
     */
    private $cantidad;

    /**
     * @ORM\Column(name="item", type="integer")
     */
    private $item;

    /**
     * @ORM\Column(name="vr_precio", type="float", nullable=true)
     */
    private $vrPrecio = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", nullable=true)
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Factura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * @param mixed $codigoFacturaDetallePk
     */
    public function setCodigoFacturaDetallePk($codigoFacturaDetallePk): void
    {
        $this->codigoFacturaDetallePk = $codigoFacturaDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * @param mixed $codigoFacturaFk
     */
    public function setCodigoFacturaFk($codigoFacturaFk): void
    {
        $this->codigoFacturaFk = $codigoFacturaFk;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item): void
    {
        $this->item = $item;
    }

    /**
     * @return mixed
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * @param mixed $vrPrecio
     */
    public function setVrPrecio($vrPrecio): void
    {
        $this->vrPrecio = $vrPrecio;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * @param mixed $vrSubtotal
     */
    public function setVrSubtotal($vrSubtotal): void
    {
        $this->vrSubtotal = $vrSubtotal;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return mixed
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * @param mixed $facturaRel
     */
    public function setFacturaRel($facturaRel): void
    {
        $this->facturaRel = $facturaRel;
    }

}

