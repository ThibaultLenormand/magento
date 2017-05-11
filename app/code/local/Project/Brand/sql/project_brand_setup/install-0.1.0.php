<?php

$installer = $this;
$installer->startSetup();

$brandTable = $installer->getConnection()
    ->newTable($installer->getTable('project_brand/brand'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'primary'  => true,
    ))
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'unique' => true
    ))
    ->addColumn('slug', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'unique' => true
    ))
    ->addColumn('image_url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ))
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array());

$installer->getConnection()->createTable($brandTable);

$installer->endSetup();
