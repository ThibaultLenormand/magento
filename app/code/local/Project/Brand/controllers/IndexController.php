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
        $brand = Mage::getResourceModel('project_brand/brand')->loadInstanceBySlug($slug);
        if(!$brand){
            $this->norouteAction();
            return;
        }
        //Get brand products
        $brand['products'] = '';
        
        //Save $brand into registry
        Mage::register('brand', $brand);
        //Render view
        $this->loadLayout();
        $this->renderLayout();

    }
}