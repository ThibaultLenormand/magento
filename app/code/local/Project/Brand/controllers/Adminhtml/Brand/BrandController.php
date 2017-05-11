<?php

class Project_Brand_Adminhtml_Brand_BrandController extends Mage_Adminhtml_Controller_Action
{

    /**
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        return $this->loadLayout()->_setActiveMenu('project_brand');
    }

    /**
     * @return Mage_Core_Controller_Varien_Action
     */
    public function indexAction()
    {
        return $this->_initAction()->renderLayout();
    }

    /**
     * @return $this
     */
    public function newAction()
    {
        $this->_forward('edit');

        return $this;
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var Project_Brand_Model_Brand $brand */
        $brand = Mage::getModel('project_brand/brand')->load($id);

        if ($brand->getId() || $id == 0) {

            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $brand->setData($data);
            }
            Mage::register('brand_data', $brand);

            return $this->_initAction()->renderLayout();
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('project_brand')->__('Brand does not exist'));

        return $this->_redirect('*/*/');
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $delete = (!isset($data['image_url']['delete']) || $data['image_url']['delete'] != '1') ? false : true;
            $data['image_url'] = $this->_saveImage('image_url', $delete);

            /** @var Project_Brand_Model_Brand $brand */
            $brand = Mage::getModel('project_brand/brand');

            if ($id = $this->getRequest()->getParam('id')) {
                $brand->load($id);
            }

            try {
                $brand->addData($data);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $brand->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $brand->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('project_brand')->__('The brand has been saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array(
                        'id'       => $brand->getId(),
                        '_current' => true
                    ));

                    return;
                }

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->addException($e, Mage::helper('project_brand')->__('An error occurred while saving the brand.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array(
                'id' => $this->getRequest()->getParam('id')
            ));

            return;
        }
        $this->_redirect('*/*/');
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                /** @var Project_Brand_Model_Brand $brand */
                $brand = Mage::getModel('project_brand/brand');
                $brand->load($id)->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('project_brand')->__('Brand was successfully deleted'));
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            }
        }

        return $this->_redirect('*/*/');
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function massDeleteAction()
    {
        $brandIds = $this->getRequest()->getParam('brand');
        if (!is_array($brandIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('project_brand')->__('Please select brand(s)'));
        } else {
            try {
                foreach ($brandIds as $brand) {
                    Mage::getModel('project_brand/brand')->load($brand)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('project_brand')->__('Total of %d brand(s) were successfully deleted', count($brandIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        return $this->_redirect('*/*/index');
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function massStatusAction()
    {
        $brandIds = $this->getRequest()->getParam('brand');
        if (!is_array($brandIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select brand(s)'));
        } else {
            try {
                foreach ($brandIds as $brand) {
                    Mage::getSingleton('project_brand/brand')->load($brand)->setIsActive($this->getRequest()->getParam('is_active'))->setIsMassupdate(true)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('project_brand')->__('Total of %d brand(s) were successfully updated', count($brandIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        return $this->_redirect('*/*/index');
    }

    /**
     *
     */
    protected function _saveImage($imageAttr, $delete)
    {
        if ($delete) {
            $image = '';
        } elseif (isset($_FILES[$imageAttr]['name']) && $_FILES[$imageAttr]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($imageAttr);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'png'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = Mage::getBaseDir('media') . DS . 'brand' . DS;
                $uploader->save($path, $_FILES[$imageAttr]['name']);
                $image = $_FILES[$imageAttr]['name'];
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                return $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        } else {
            $model = Mage::getModel('project_brand/brand')->load($this->getRequest()->getParam('id'));
            $image = $model->getData($imageAttr);
        }
        return $image;
    }

    protected function _initBrand()
    {
        $id = $this->getRequest()->getParam('id');
        $brand = Mage::getModel('project_brand/brand')->load($id);
        Mage::register('current_brand', $brand);
    }

    public function productsAction(){
        $this->_initBrand(); //if you don't have such a method then replace it with something that will get you the entity you are editing.
        $this->loadLayout();
        $this->getLayout()->getBlock('brand.edit.tab.product')
            ->setBrandProducts($this->getRequest()->getPost('brand_products', null));
        $this->renderLayout();
    }

    public function productsgridAction(){
        $this->_initBrand();
        $this->loadLayout();
        $this->getLayout()->getBlock('brand.edit.tab.product')
            ->setBrandProducts($this->getRequest()->getPost('brand_products', null));
        $this->renderLayout();
    }
}
