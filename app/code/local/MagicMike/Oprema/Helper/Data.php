<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 29.04.17.
 * Time: 18:23
 */ 
class MagicMike_Oprema_Helper_Data extends Mage_Core_Helper_Abstract
{
    const API_BASE_URL = 'base_url';

    public function createUrl($url){
        return $this->getBaseUrl(). '/' . $url;
    }

    public function getBaseUrl(){
        return Mage::getStoreConfig( self::API_BASE_URL);
    }
}