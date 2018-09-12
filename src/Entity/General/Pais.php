<?php

namespace App\Entity\General;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pais")
 * @ORM\Entity(repositoryClass="App\Repository\General\PaisRepository")
 */
class Pais
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pais_pk", type="integer")
     */
    private $codigoPaisPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un pais")
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="Departamento", mappedBy="paisRel")
     */
    protected $departamentosRel;

    /**
     * @return mixed
     */
    public function getCodigoPaisPk()
    {
        return $this->codigoPaisPk;
    }

    /**
     * @param mixed $codigoPaisPk
     */
    public function setCodigoPaisPk($codigoPaisPk): void
    {
        $this->codigoPaisPk = $codigoPaisPk;
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
    public function getDepartamentosRel()
    {
        return $this->departamentosRel;
    }

    /**
     * @param mixed $departamentosRel
     */
    public function setDepartamentosRel($departamentosRel): void
    {
        $this->departamentosRel = $departamentosRel;
    }


}
