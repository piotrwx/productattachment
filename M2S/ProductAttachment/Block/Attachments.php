<?php

namespace M2S\ProductAttachment\Block;

use M2S\ProductAttachment\Helper\Data;
use M2S\ProductAttachment\Model\ResourceModel\Item\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class Attachments extends Template
{
    private $collectionFactory;

    private $helperData;

    private $_coreRegistry;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        Data $helperData,
        Registry $registry,
        array $data = []
    ) {
        $this->helperData = $helperData;
        $this->_coreRegistry = $registry;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    public function getItems()
    {
        $sku = $this->getProduct()->getSku();
        return $this->collectionFactory->create()->addStatusFilter(1)->addProductFilter($sku)->getItems();
    }

    public function getItemByType($item)
    {
        return $item->getAttachmentType();
    }

    public function getAction()
    {
        return $this->helperData->getActionUrl();
    }

    public function isEnabled()
    {
        return $this->helperData->isEnabled();
    }

    public function isEnabledCustomerAttachment()
    {
        return $this->helperData->isEnabledCustomerAttachment();
    }
}
