<?php
class Project_Brand_Model_Resource_Brand_Product_Collection
    extends Mage_Catalog_Model_Resource_Product_Collection {
    protected $_joinedFields = false;
    public function joinFields(){
        if (!$this->_joinedFields){
            $this->getSelect()->join(
                array('related' => $this->getTable('project_brand/brand_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }
    public function addBrandFilter($brand){
        if ($brand instanceof Project_Brand_Model_Brand){
        $brand = $brand->getId();
    }
        if (!$this->_joinedFields){
            $this->joinFields();
        }
        $this->getSelect()->where('related.brand_id = ?', $brand);
        return $this;
    }
}
