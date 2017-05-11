<?php

class Project_Brand_Block_Brand extends Mage_Core_Block_Template
{
    public function getBrands()
    {
        $brands = Mage::getModel('project_brand/brand')
            ->getCollection()
            ->addIsActiveFilter();

        return $brands;
    }
}
