<?php

namespace App\Objects;

use DateTime;

class MerchantInfoUS extends MerchantInfo
{
    /**
     * @var string
     */
    protected $socialSecurityNumber;

    /**
     * @var bool
     */
    protected $socialSecurityNumberIsPresent = false;

    /**
     * @var DateTime
     */
    protected $dateOfBirth;

    /**
     * @var bool
     */
    protected $dateOfBirthIsPresent = false;

    /**
     * @return DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param DateTime $dateOfBirth
     */
    public function setDateOfBirth(DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return string
     */
    public function getSocialSecurityNumber()
    {
        return $this->socialSecurityNumber;
    }

    /**
     * @param string $socialSecurityNumber
     */
    public function setSocialSecurityNumber(string $socialSecurityNumber)
    {
        $this->socialSecurityNumber = $socialSecurityNumber;
    }

    /**
     * @return bool
     */
    public function isSocialSecurityNumberIsPresent()
    {
        return $this->socialSecurityNumberIsPresent;
    }

    /**
     * @param bool $socialSecurityNumberIsPresent
     */
    public function setSocialSecurityNumberIsPresent(bool $socialSecurityNumberIsPresent): void
    {
        $this->socialSecurityNumberIsPresent = $socialSecurityNumberIsPresent;
    }

    /**
     * @return bool
     */
    public function isDateOfBirthIsPresent()
    {
        return $this->dateOfBirthIsPresent;
    }

    /**
     * @param bool $dateOfBirthIsPresent
     */
    public function setDateOfBirthIsPresent(bool $dateOfBirthIsPresent): void
    {
        $this->dateOfBirthIsPresent = $dateOfBirthIsPresent;
    }
}