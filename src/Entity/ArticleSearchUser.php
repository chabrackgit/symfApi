<?php

namespace App\Entity;

class ArticleSearchUser {
    
    /**
     * info
     *
     * @var mixed
     */
    private $info;
    

    /**
     * Get info
     *
     * @return  mixed
     */ 
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set info
     *
     * @param  mixed  $info  info
     *
     * @return  self
     */ 
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }
}