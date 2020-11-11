<?php

namespace App\Entity;

use App\Entity\Catalog;

class ArticleSearch {
    
    /**
     * infoCatalog
     *
     * @var Catalog|null
     */
    private $infoCatalog;
    
    /**
     * info
     *
     * @var string|null
     */
    private $info;
    



    /**
     * Get infoCatalog
     *
     * @return  Catalog|null
     */ 
    public function getInfoCatalog()
    {
        return $this->infoCatalog;
    }

    /**
     * Set infoCatalog
     *
     * @param  Catalog|null  $infoCatalog  infoCatalog
     *
     * @return  self
     */ 
    public function setInfoCatalog($infoCatalog)
    {
        $this->infoCatalog = $infoCatalog;

        return $this;
    }

    

    /**
     * Get info
     *
     * @return  string|null
     */ 
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set info
     *
     * @param  string|null  $info  info
     *
     * @return  self
     */ 
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }
}