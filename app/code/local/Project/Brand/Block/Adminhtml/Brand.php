<?php

class Project_Brand_Block_Adminhtml_Brand extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller     = 'adminhtml_brand';
        $this->_blockGroup     = 'project_brand';
        $this->_headerText     = Mage::helper('project_brand')->__('Manage Brands');
        $this->_addButtonLabel = Mage::helper('project_brand')->__('Add Brand');
        parent::__construct();
    }
}
