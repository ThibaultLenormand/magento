<?php

class Project_Brand_Model_Resource_Brand extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * Magento class constructor
     */
    protected function _construct()
    {
        $this->_init('project_brand/brand', 'entity_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        // Slug generation
        $slug = Mage::getModel('catalog/product_url')->formatUrlKey( $object->getName() );
        $isUnique = $this->isInstanceUnique($slug, $object->getId());

        if(!$isUnique) {
            throw new Exception("Slug already exists");
        }

        $object->setSlug($slug);
        return parent::_beforeSave($object); // TODO: Change the autogenerated stub
    }

    protected function isInstanceUnique($slug, $id)
    {
        $reader = $this->_getReadAdapter();
        $select = $reader->select()->from($this->getMainTable())->where('slug = ?', $slug)->where('entity_id != ?', $id);

        $result = $reader->fetchOne($select);

        return !$result;
    }
    
    public function loadInstanceBySlug($slug, $model)
    {
        $reader = $this->_getReadAdapter();
        $select = $reader->select()->from($this->getMainTable())->where('slug = ?', $slug);

        $result = $reader->fetchRow($select);

        if($result){
            $model->setData($result);
        }
        
        return $this;
    }
}
