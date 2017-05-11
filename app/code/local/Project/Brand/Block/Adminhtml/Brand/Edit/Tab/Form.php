<?php

class Project_Brand_Block_Adminhtml_Brand_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('brand_form', array('legend' => Mage::helper('project_brand')->__('Brand information')));

        $fieldset->addType('image', 'Project_Brand_Block_Adminhtml_Form_Renderer_Image');

        $fieldset->addField('name', 'text', array(
            'label'    => Mage::helper('project_brand')->__('Name'),
            'name'     => 'name',
            'class'    => 'required-entry',
            'required' => true
        ));

        $fieldset->addField('image_url', 'image', array(
            'label'     => Mage::helper('project_brand')->__('Image'),
            'required'  => false,
            'name'      => 'image_url',
            'directory' => 'brand/'
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'    => Mage::helper('project_brand')->__('Status'),
            'name'     => 'is_active',
            'class'    => 'required-entry',
            'values'   => Mage::getSingleton('adminhtml/system_config_source_enabledisable')->toOptionArray(),
            'required' => true
        ));

        if (Mage::getSingleton('adminhtml/session')->getBrandData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBrandData());
            Mage::getSingleton('adminhtml/session')->getBrandData(null);
        } elseif (Mage::registry('brand_data')) {
            $form->setValues(Mage::registry('brand_data')->getData());
        }

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return Mage::helper('project_brand')->__('Brand Information');
    }

    public function getTabTitle()
    {
        return Mage::helper('project_brand')->__('Brand Information');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}