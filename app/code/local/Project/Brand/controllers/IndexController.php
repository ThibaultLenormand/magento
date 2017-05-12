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
        /*
        $this->getParams(name) pour obtenir slug
        instancier le model brand
        tester l'objet
        */

        $slug = $this->getRequest()->getParam('name');
        var_dump($slug);

        $brand = Mage::getResourceModel('project_brand/brand')->loadInstanceBySlug($slug);
        var_dump($brand);

        $this->loadLayout();
        $this->renderLayout();

    }
}