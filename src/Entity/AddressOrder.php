<?php

namespace App\Entity;

use App\Entity\Address;

class AddressOrder {
    
    /**
     * infoAddress
     *
     * @var Address|null
     */
    private $infoAddress;
     

    /**
     * Get infoAddress
     *
     * @return  Address|null
     */ 
    public function getInfoAddress()
    {
        return $this->infoAddress;
    }

    /**
     * Set infoAddress
     *
     * @param  Address|null  $infoAddress  infoAddress
     *
     * @return  self
     */ 
    public function setInfoAddress($infoAddress)
    {
        $this->infoAddress = $infoAddress;

        return $this;
    }
}