<?php

namespace App\Entity;

use App\Repository\ContatoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContatoRepository::class)]
class Contato {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $tipo = null;

    #[ORM\Column(length: 255)]
    private ?string $descricao = null;

    #[ORM\ManyToOne(inversedBy: 'contatos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pessoa $Pessoa = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function isTipo(): ?bool {
        return $this->tipo;
    }

    public function setTipo(bool $tipo): self {
        $this->tipo = $tipo;
        return $this;
    }

    public function getDescricao(): ?string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self {
        $this->descricao = $descricao;
        return $this;
    }

    public function getPessoa(): ?Pessoa {
        return $this->Pessoa;
    }

    public function setPessoa(?Pessoa $oPessoa): self {
        $this->Pessoa = $oPessoa;
        return $this;
    }
    
}