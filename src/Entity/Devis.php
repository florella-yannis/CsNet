<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DevisRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 11)]
    private ?string $numberdevis = null;

    #[ORM\Column(length: 255)]
    private ?string $client = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $society = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(length: 5)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $intervention = null;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: DetailDevis::class)]
    private Collection $detaildevis;

    public function __construct()
    {
        $this->detaildevis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberdevis(): ?string
    {
        return $this->numberdevis;
    }

    public function setNumberdevis(string $currentYear, int $nextDevisNumber): self
    {
        $this->numberdevis = sprintf('N°%d-%03d-%03d', $currentYear, $nextDevisNumber, $this->id);

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSociety(): ?string
    {
        return $this->society;
    }

    public function setSociety(?string $society): self
    {
        $this->society = $society;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIntervention(): ?string
    {
        return $this->intervention;
    }

    public function setIntervention(string $intervention): self
    {
        $this->intervention = $intervention;

        return $this;
    }

    /**
     * @return Collection<int, DetailDevis>
     */
    public function getDetaildevis(): Collection
    {
        return $this->detaildevis;
    }

    public function addDetaildevi(DetailDevis $detaildevi): self
    {
        if (!$this->detaildevis->contains($detaildevi)) {
            $this->detaildevis->add($detaildevi);
            $detaildevi->setDevis($this);
        }

        return $this;
    }

    public function removeDetaildevi(DetailDevis $detaildevi): self
    {
        if ($this->detaildevis->removeElement($detaildevi)) {
            // set the owning side to null (unless already changed)
            if ($detaildevi->getDevis() === $this) {
                $detaildevi->setDevis(null);
            }
        }

        return $this;
    }
}
