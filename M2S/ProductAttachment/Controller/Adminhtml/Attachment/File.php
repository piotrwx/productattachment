<?php

namespace M2S\ProductAttachment\Controller\Adminhtml\Attachment;

use M2S\ProductAttachment\Model\ItemFactory;
use Magento\Backend\App\Action;

class File extends Action
{
    protected $itemFactory;

    public function __construct(
        ItemFactory $itemFactory,
        Action\Context $context
    ) {
        $this->itemFactory = $itemFactory;
        parent::__construct($context);
    }

    public function execute()
    {

    }
}
