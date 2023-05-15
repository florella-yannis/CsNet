<?php

namespace App\Entity;

use App\Repository\DetailDevisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailDevisRepository::class)]
class DetailDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column(length: 5)]
    private ?string $priceunit = null;

    #[ORM\Column(length: 3)]
    private ?string $quantity = null;

    #[ORM\Column(length: 5)]
    private ?string $pricetotal = null;

    #[ORM\Column(length: 5)]
    private ?string $pricefinal = null;

    #[ORM\ManyToOne(inversedBy: 'detaildevis')]
    private ?Devis $devis = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPriceunit(): ?string
    {
        return $this->priceunit;
    }

    public function setPriceunit(string $priceunit): self
    {
        $this->priceunit = $priceunit;
        $this->calculateTotalPrice();
    

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;
    $this->calculateTotalPrice();

        return $this;
    }

    public function getPricetotal(): ?string
    {
        return $this->pricetotal;
    }

    public function setPricetotal(string $pricetotal): self
    {
        $this->pricetotal = $pricetotal;

        return $this;
    }

    public function getPricefinal(): ?string
    {
        return $this->pricefinal;
    }

    public function setPricefinal(string $pricefinal): self
    {
        $this->pricefinal = $pricefinal;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        return $this;
    }

    public function calculateTotalPrice(): void
{
    if ($this->priceunit && $this->quantity) {
        $this->pricetotal = $this->priceunit * $this->quantity;
    } else {
        $this->pricetotal = null;
    }
}


    public function updateFinalPrice(): void
{
    $totalPrice = 0;

    foreach ($this->getDevis()->getDetaildevis() as $detailDevis) {
        $totalPrice += $detailDevis->getPricetotal();
    }

    $this->setPricefinal($totalPrice);
}
}
