<?php

namespace M2S\ProductAttachment\Block\Adminhtml\Product\Edit\Tab;

use M2S\ProductAttachment\Block\Attachments;
use M2S\ProductAttachment\Helper\Data;
use M2S\ProductAttachment\Model\ResourceModel\Item\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class Attachment extends Attachments
{
    protected $_template = 'attachment_items.phtml';

    public function __construct(Template\Context $context, CollectionFactory $collectionFactory, Data $helperData, Registry $registry, array $data = [])
    {
        parent::__construct($context, $collectionFactory, $helperData, $registry, $data);
    }

    public function getItems()
    {
        return parent::getItems();
    }

    public function getItemByType($item)
    {
        return parent::getItemByType($item);
    }
}
