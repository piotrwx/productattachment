<?php


namespace M2S\ProductAttachment\Model;


use Magento\Framework\Model\AbstractModel;

class Item extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\M2S\ProductAttachment\Model\ResourceModel\Item::class);
    }
}
