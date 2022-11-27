<?php

declare(strict_types=1);

namespace M2S\ProductAttachment\Block;

use M2S\ProductAttachment\Helper\Data;
use M2S\ProductAttachment\Model\ResourceModel\Item\CollectionFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Attachments extends Template
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Data
     */
    private $helperData;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Data $helperData
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        Data $helperData,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->helperData = $helperData;
        $this->registry = $registry;
    }

    /**
     * Retrieve product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return mixed
     */
    public function getItems(): mixed
    {
        $sku = $this->getProduct()->getSku();
        return $this->collectionFactory->create()->addStatusFilter(1)->addProductFilter($sku)->getItems();
    }

    /**
     * @param $item
     * @return mixed
     */
    public function getItemByType($item): mixed
    {
        return $item->getAttachmentType();
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->helperData->getActionUrl();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->helperData->isEnabled();
    }

    /**
     * @return bool
     */
    public function isEnabledCustomerAttachment(): bool
    {
        return (bool) $this->helperData->isEnabledCustomerAttachment();
    }
}
