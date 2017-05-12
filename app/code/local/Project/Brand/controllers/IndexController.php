<?php

class Project_Brand_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewAction()
    {
        //Get the slug param
        $slug = $this->getRequest()->getParam('name');
        if(!$slug){
            $this->norouteAction();
            return;
        }
        //Get brand
        $brand = Mage::getModel('project_brand/brand')->loadInstanceByAttribute($slug);
        if($brand['entity_id'] == null){
            $this->norouteAction();
            return;
        }
        //If brand disabled
        if(!$brand['is_active']){
            $this->norouteAction();
            return;            
        }
        //Get brand products
        $products = $brand->getSelectedProductsCollection();
        
        //Save $brand into registry
        Mage::register('brand', $brand);
        Mage::register('products', $products);
        //Render view
        $this->loadLayout();
        $this->renderLayout();
    }
}