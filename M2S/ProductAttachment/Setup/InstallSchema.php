<?php

namespace M2S\ProductAttachment\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable('m2s_product_attachment')
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'product_sku',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Product sku'
        )->addColumn(
            'image',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'image'
        )->addColumn(
                'attachment_path',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'attachment'
            )->addColumn(
            'attachment_type',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'type of attachment'
        )->addColumn(
            'file_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'file name'
        )->addColumn(
            'status',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            '0 = disable / 1 = enable'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'added at'
        )->setComment('M2S Product Attachment');
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
