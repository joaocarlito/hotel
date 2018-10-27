<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Cliente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type(
     *     type="alpha",
     *     message="O nome {{ value }} não é válido."
     * )
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type(
     *     type="alpha",
     *     message="O nome {{ value }} não é válido."
     * )
     */
    private $sobrenome;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email(
     *     message = "O email '{{ value }}' não é valido.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $endereco;

    public function __toString()
    {
       return $this->nome . " " . $this->sobrenome;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getSobrenome(): ?string
    {
        return $this->sobrenome;
    }

    public function setSobrenome(string $sobrenome): self
    {
        $this->sobrenome = $sobrenome;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEndereco(): ?string
    {
        return $this->endereco;
    }

    public function setEndereco(string $endereco): self
    {
        $this->endereco = $endereco;

        return $this;
    }
}