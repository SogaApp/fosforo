<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\FacturaTipoRepository")
 */
class FacturaTipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoFacturaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Factura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * @param mixed $codigoFacturaTipoPk
     */
    public function setCodigoFacturaTipoPk($codigoFacturaTipoPk): void
    {
        $this->codigoFacturaTipoPk = $codigoFacturaTipoPk;
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
    public function getFacturasFacturaTipoRel()
    {
        return $this->facturasFacturaTipoRel;
    }

    /**
     * @param mixed $facturasFacturaTipoRel
     */
    public function setFacturasFacturaTipoRel($facturasFacturaTipoRel): void
    {
        $this->facturasFacturaTipoRel = $facturasFacturaTipoRel;
    }

}

