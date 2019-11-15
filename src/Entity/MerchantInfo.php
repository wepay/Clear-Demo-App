<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MerchantInfoRepository")
 */
class MerchantInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $legal_entity_id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="merchantInfo", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $account_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payoutId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLegalEntityId(): ?string
    {
        return $this->legal_entity_id;
    }

    public function setLegalEntityId(string $legal_entity_id): self
    {
        $this->legal_entity_id = $legal_entity_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAccountId(): ?string
    {
        return $this->account_id;
    }

    public function setAccountId(?string $account_id): self
    {
        $this->account_id = $account_id;

        return $this;
    }

    public function getPayoutId(): ?string
    {
        return $this->payoutId;
    }

    public function setPayoutId(?string $payoutId): self
    {
        $this->payoutId = $payoutId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
