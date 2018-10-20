<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */

class Quarto 
{
     /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * Se o quarto é solteiro, duplo, casal, etc
     * @var string 
     * @ORM\Column(type="string", length=50)
     */
    private $tipo;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;
    
    /**
     * @ORM\Column(type="text")
     */
    private $descricao;
    
    /**
     * @ORM\Column(type="array")
     */
    private $fotos;
    
    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $diaria;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reserva", mappedBy="quarto")
     */
    private $reservas;


    public function __toString()
    {
        return $this->nome;
    }


    public function __construct()
    {
        $this->reservas = new ArrayCollection();
    }

    
    
    
    function getId() 
    {
        return $this->id;
    }

    function getTipo() 
    {
        return $this->tipo;
    }

    function getNome() 
    {
        return $this->nome;
    }

    function getDescricao() 
    {
        return $this->descricao;
    }

    function getFotos()
    {
        return $this->fotos;
    }

    function getDiaria() 
    {
        return $this->diaria;
    }

    function setTipo($tipo) 
    {
        $this->tipo = $tipo;
        return $this;
    }

    function setNome($nome) 
    {
        $this->nome = $nome;
        return $this;
    }

    function setDescricao($descricao) 
    {
        $this->descricao = $descricao;
        return $this;
    }

    function setFotos($fotos) 
    {
        $this->fotos = $fotos;
        return $this;
    }

    /**
     * 
     * @param float $diaria
     * @return $this
     */
    function setDiaria($diaria) 
    {
        $this->diaria = $diaria;
        return $this;
    }

    /**
     * @return Collection|Reserva[]
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas[] = $reserva;
            $reserva->setQuarto($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->contains($reserva)) {
            $this->reservas->removeElement($reserva);
            // set the owning side to null (unless already changed)
            if ($reserva->getQuarto() === $this) {
                $reserva->setQuarto(null);
            }
        }

        return $this;
    }

}

