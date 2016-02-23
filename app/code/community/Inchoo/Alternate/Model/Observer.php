<?php
class Inchoo_Alternate_Model_Observer
{
    public function alternateLinks()
    {
        $headBlock = Mage::app()->getLayout()->getBlock('head');

        $stores = Mage::app()->getStores();
        $prod = Mage::registry('current_product');
        $categ = Mage::registry('current_category');
        $cms = Mage::getSingleton('cms/page')->getIdentifier();

        $languageRefs = [
            'au' => "en-AU",
            'ca_en' => "en-CA",
            'ca_fr' => "fr-CA",
            'dk' => "da",
            'default' => "en-GB",
            'en' => "en",
            'ro' => "ro",
            'no' => "no" ,
            'se' => "sv" ,
            'us' => "en-US" ,
            'fi' => "fi" ,
            'fr' => "fr" ,
            'de' => "de",
            'it' => "it",
            'nl' => "nl"
        ];
        $currentStoreCode = Mage::app()->getStore()->getCode();

        if($headBlock){
            foreach ($stores as $store){
                $storeCode = $store->getCode();
                if( $currentStoreCode !== $storeCode) {
                    if ($prod) {
                        $categ ? $categId = $categ->getId() : $categId = null;
                        $url = $store->getBaseUrl() . Mage::helper('inchoo_alternate')
                                ->rewrittenProductUrl($prod->getId(), $categId, $store->getId())
                        ;
                    } elseif ($categ) {
                        $url = $store->getBaseUrl() . Mage::helper('inchoo_alternate')
                                ->rewrittenCategoryUrl($categ->getId(), $store->getId());
                    } elseif ($cms) {
                        $url = $store->getBaseUrl() . Mage::helper('inchoo_alternate')
                                ->rewrittenCmsUrl($cms, $store->getId());
                    } else {
                        $url = $store->getCurrentUrl();
                    }
                    $urlPart = explode('?', $url, 2)[0];
                    //$storeCode = substr(Mage::getStoreConfig('general/locale/code', $store->getId()),0,2);
                    $headBlock->addLinkRel('alternate"' . ' hreflang="' . $languageRefs[$storeCode], $urlPart);
                }
            }
        }
        return $this;
    }
}