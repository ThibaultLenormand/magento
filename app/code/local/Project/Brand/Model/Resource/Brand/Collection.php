<?php

class Project_Brand_Model_Resource_Brand_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Magento class constructor
     */
    protected function _construct()
    {
        $this->_init('project_brand/brand');
    }

    /**
     * Filter collection by status
     *
     * @return Project_Brand_Model_Resource_Brand_Collection
     */
    public function addIsActiveFilter()
    {
        $this->addFieldToFilter('is_active', 1);

        return $this;
    }
}