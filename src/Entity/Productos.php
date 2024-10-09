<?php

namespace App\Entity;

use App\Repository\ProductosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Webmozart\Assert\Assert as AssertAssert;

#[ORM\Entity(repositoryClass: ProductosRepository::class)]
class Productos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id_producto = null;

    #[ORM\Column]
    public ?int $clave_producto = null;

    #[ORM\Column(length: 250)]
    public ?string $nombre = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    public ?string $precio = null;

    public function getId(): ?int
    {
        return $this->id_producto;
    }

    public function getClave(): ?int
    {
        return $this->clave_producto;
    }

    public function setClave(int $clave_producto): static
    {
        $this->clave_producto = $clave_producto;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // NotBlank para validar que no esté vacío
        $metadata->addPropertyConstraint('clave_producto', new Assert\NotBlank(['message' => 'Este campo no puede quedar vacío']));
        $metadata->addPropertyConstraint('nombre', new Assert\NotBlank(['message' => 'Este campo no puede quedar vacío']));
        $metadata->addPropertyConstraint('precio', new Assert\NotBlank(['message' => 'Este campo no puede quedar vacío']));

        // Regex para validar que solo se ingresen números
        $metadata->addPropertyConstraint('clave_producto', new Regex([
            'pattern' => '/^\d+$/',
            'message' => 'En este campo solo se ingresan números'
        ]));
    }
}
