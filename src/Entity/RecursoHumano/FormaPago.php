<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\FormaPagoRepository")
 */
class FormaPago
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoFormaPagoPk;

    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="dias", type="integer", nullable=true)
     */
    private $dias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\Tercero", mappedBy="formaPagoRel")
     */
    protected $tercerosFormaPagoRel;

    /**
     * @return mixed
     */
    public function getCodigoFormaPagoPk()
    {
        return $this->codigoFormaPagoPk;
    }

    /**
     * @param mixed $codigoFormaPagoPk
     */
    public function setCodigoFormaPagoPk($codigoFormaPagoPk): void
    {
        $this->codigoFormaPagoPk = $codigoFormaPagoPk;
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
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
    }

    /**
     * @return mixed
     */
    public function getTercerosFormaPagoRel()
    {
        return $this->tercerosFormaPagoRel;
    }

    /**
     * @param mixed $tercerosFormaPagoRel
     */
    public function setTercerosFormaPagoRel($tercerosFormaPagoRel): void
    {
        $this->tercerosFormaPagoRel = $tercerosFormaPagoRel;
    }


}

