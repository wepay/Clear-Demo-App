<?php

namespace App\Objects;

class MerchantInfo
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var boolean
     */
    protected $isBeneficialOwner;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return bool
     */
    public function isBeneficialOwner()
    {
        return $this->isBeneficialOwner;
    }

    /**
     * @param bool $isBeneficialOwner
     */
    public function setIsBeneficialOwner($isBeneficialOwner)
    {
        $this->isBeneficialOwner = $isBeneficialOwner;
    }
}