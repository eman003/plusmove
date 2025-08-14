<?php

namespace App\Faker;

use App\Facades\Address;
use Faker\Provider\Base;

class AddressProvider extends Base
{
    protected static $streetSuffixes = ['Avenue', 'Boulevard', 'Drive', 'Road', 'Street'];
    public function addressLine1(): string
    {
        return $this->generator->numberBetween(0001, 9999) .' '. $this->generator->streetName().' '. $this->generator->randomElement(static::$streetSuffixes);
    }

    public function complex(): string
    {
        return $this->generator->buildingNumber().' '.$this->generator->randomElement(Address::complexNames(20));
    }

    public function suburb(): string
    {
        return Address::addressElement()->suburb;
    }

    public function citySA(): string
    {
        return Address::addressElement()->city;
    }

    public function province(): string
    {
        return Address::addressElement()->province;
    }

}
