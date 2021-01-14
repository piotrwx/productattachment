<?php

namespace M2S\ProductAttachment\Model\ResourceModel\Item;

use M2S\ProductAttachment\Model\ResourceModel\Item as ItemResource;
use M2S\ProductAttachment\Model\Item;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Item::class, ItemResource::class);
    }
}
