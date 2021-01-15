<?php

namespace M2S\ProductAttachment\Model\ResourceModel\Item;

use M2S\ProductAttachment\Model\Item;
use M2S\ProductAttachment\Model\ResourceModel\Item as ItemResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Item::class, ItemResource::class);
    }

    public function addStatusFilter($status)
    {
        $condition = $this->getConnection()->quoteInto('status=?', $status);
        $this->addFilter('status', $condition, 'string');
        return $this;
    }
    public function addProductFilter($productSku)
    {
        $condition = $this->getConnection()->quoteInto('product_sku=?', $productSku);
        $this->addFilter('product_sku', $condition, 'string');
        return $this;
    }

}
