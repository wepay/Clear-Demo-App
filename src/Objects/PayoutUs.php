<?php

namespace App\Objects;

class PayoutUs extends Payout
{
    /**
     * @var string
     */
    protected $routingNumber;

    /**
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->routingNumber;
    }

    /**
     * @param string $routingNumber
     */
    public function setRoutingNumber($routingNumber): void
    {
        $this->routingNumber = $routingNumber;
    }
}