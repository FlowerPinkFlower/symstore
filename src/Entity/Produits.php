<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitsRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 */
class Produits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("prods:read") 
     */
    //Importation use de groupe!!
    //Le mot qui permet de récuprer les produits est la lecture read. Le principal est de mettre cette info dans tous les champs que je vex intégrer à ce groupe la.
    //Ce sont des annotations
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("prods:read", "orders:read") 
     * @Assert\NotBlank(message="Le nom du produit doit être indiqué")
     * @Assert\Length(min=10,
     * minMessage="Le nom du produit doit contenir au moins 3 caractères",
     * max=255,
     * maxMessage="Le nom du produit doit contenir au maximum 255 caractères"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups("prods:read") 
     * @Assert\PositiveOrZero(message="La quantité doit être >= 0")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     * @Groups("prods:read")
     */
    private $unit_price;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="Produits")
     * @Groups("prods:read")
     */
    //AVANT SOLUTION 3 Je n'inclus pas dans le groupe car je vais repartir sur une référence circulaire
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unit_price;
    }

    public function setUnitPrice(float $unit_price): self
    {
        $this->unit_price = $unit_price;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
