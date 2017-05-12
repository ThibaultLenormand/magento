<?php

class Project_Brand_Model_Brand_Product extends Mage_Core_Model_Abstract {
    protected function _construct(){
        $this->_init('project_brand/brand_product');
    }
    public function saveBrandRelation($brand){
    $data = $brand->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveBrandRelation($brand, $data);
        }
        return $this;
    }
    public function getProductCollection($brand){
        $collection = Mage::getResourceModel('project_brand/brand_product_collection')
            ->addBrandFilter($brand);
        return $collection;
    }
}
