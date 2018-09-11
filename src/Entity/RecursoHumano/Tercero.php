<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\TerceroRepository")
 */
class Tercero
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoTerceroPk;

    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */
    private $codigoFormaPagoFk;

    /**
     * @ORM\Column(name="nit", type="integer", nullable=true)
     */
    private $nit;

    /**
     * @ORM\Column(name="nombre", type="float", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\FormaPago", inversedBy="tercerosFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="Factura", mappedBy="terceroRel")
     */
    protected $facturasTerceroRel;

    /**
     * @return mixed
     */
    public function getCodigoTerceroPk()
    {
        return $this->codigoTerceroPk;
    }

    /**
     * @param mixed $codigoTerceroPk
     */
    public function setCodigoTerceroPk($codigoTerceroPk): void
    {
        $this->codigoTerceroPk = $codigoTerceroPk;
    }

    /**
     * @return mixed
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     */
    public function setNit($nit): void
    {
        $this->nit = $nit;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * @param mixed $codigoFormaPagoFk
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk): void
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;
    }

    /**
     * @return mixed
     */
    public function getFacturasTerceroRel()
    {
        return $this->facturasTerceroRel;
    }

    /**
     * @param mixed $facturasTerceroRel
     */
    public function setFacturasTerceroRel($facturasTerceroRel): void
    {
        $this->facturasTerceroRel = $facturasTerceroRel;
    }

    /**
     * @return mixed
     */
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * @param mixed $formaPagoRel
     */
    public function setFormaPagoRel($formaPagoRel): void
    {
        $this->formaPagoRel = $formaPagoRel;
    }


}

