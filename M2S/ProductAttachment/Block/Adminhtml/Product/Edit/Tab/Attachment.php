<?php

declare(strict_types=1);

namespace M2S\ProductAttachment\Block\Adminhtml\Product\Edit\Tab;

use M2S\ProductAttachment\Block\Attachments;
use M2S\ProductAttachment\Helper\Data;
use M2S\ProductAttachment\Model\ResourceModel\Item\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Attachment extends Attachments
{
    /**
     * @var string
     */
    protected $_template = 'attachment_items.phtml';

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
        Data $helperData, Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $collectionFactory, $helperData, $registry, $data);
    }

    /**
     * @return mixed
     */
    public function getItems(): mixed
    {
        return parent::getItems();
    }

    /**
     * @param $item
     * @return mixed
     */
    public function getItemByType($item): mixed
    {
        return parent::getItemByType($item);
    }
}
