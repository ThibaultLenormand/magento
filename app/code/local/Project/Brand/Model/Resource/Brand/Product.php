<?php
class Project_Brand_Model_Resource_Brand_Product
    extends Mage_Core_Model_Resource_Db_Abstract {
    protected function  _construct(){
        $this->_init('project_brand/brand_product', 'rel_id');
    }
    public function saveBrandRelation($brand, $data){
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('brand_id=?', $brand->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert($this->getMainTable(), array(
                'brand_id'      => $brand->getId(),
                'product_id'     => $productId,
                'position'      => @$info['position']
            ));
        }
        return $this;
    }
}
