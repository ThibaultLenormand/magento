<?php

class Project_Brand_Model_Brand extends Mage_Core_Model_Abstract
{

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Magento class constructor
     */
    protected function _construct()
    {
        $this->_init('project_brand/brand');
    }

    protected $_productInstance = null;

    public function getProductInstance(){
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('project_brand/brand_product');
        }
        return $this->_productInstance;
    }

    protected function _afterSave() {
        $this->getProductInstance()->saveBrandRelation($this);
        return parent::_afterSave();
    }

    public function getSelectedProducts(){
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    public function getSelectedProductsCollection(){
        //Get all products
        $toRetrieve = array('name', 'productUrl', 'status', 'visibility', 'thumbnail');
        $collections = $this->getProductInstance()->getProductCollection($this)->addAttributeToSelect($toRetrieve);
        $toReturn = [];
        //Remove products disabled & remove product uncatalog
        foreach($collections as $key => $c){
            if($c->getStatus() != 2 && $c->getVisibility() != 1){
                $toReturn[] = $c;
            }
        }
        return $toReturn;
    }
    
    public function loadInstanceByAttribute($value){
        $_resource = $this->_getResource();
        $_resource->loadInstanceBySlug($value, $this);
        
        return $this;
    }

}
