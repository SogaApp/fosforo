<?php

namespace App\Entity\General;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="departamento")
 * @ORM\Entity(repositoryClass="App\Repository\General\DepartamentoRepository")
 */
class Departamento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_departamento_pk", type="integer")
     */
    private $codigoDepartamentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre")
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_pais_fk", type="integer", nullable=true)
     */
    private $codigoPaisFk;
    
    /**
     * @ORM\OneToMany(targetEntity="Ciudad", mappedBy="departamentoRel")
     */
    protected $ciudadesRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pais", inversedBy="departamentosRel")
     * @ORM\JoinColumn(name="codigo_pais_fk", referencedColumnName="codigo_pais_pk")
     */
    protected $paisRel;

    /**
     * @return mixed
     */
    public function getCodigoDepartamentoPk()
    {
        return $this->codigoDepartamentoPk;
    }

    /**
     * @param mixed $codigoDepartamentoPk
     */
    public function setCodigoDepartamentoPk($codigoDepartamentoPk): void
    {
        $this->codigoDepartamentoPk = $codigoDepartamentoPk;
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
    public function getCodigoPaisFk()
    {
        return $this->codigoPaisFk;
    }

    /**
     * @param mixed $codigoPaisFk
     */
    public function setCodigoPaisFk($codigoPaisFk): void
    {
        $this->codigoPaisFk = $codigoPaisFk;
    }

    /**
     * @return mixed
     */
    public function getCiudadesRel()
    {
        return $this->ciudadesRel;
    }

    /**
     * @param mixed $ciudadesRel
     */
    public function setCiudadesRel($ciudadesRel): void
    {
        $this->ciudadesRel = $ciudadesRel;
    }

    /**
     * @return mixed
     */
    public function getPaisRel()
    {
        return $this->paisRel;
    }

    /**
     * @param mixed $paisRel
     */
    public function setPaisRel($paisRel): void
    {
        $this->paisRel = $paisRel;
    }
}
