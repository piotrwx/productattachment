<?php

namespace M2S\ProductAttachment\Controller\Adminhtml\Attachment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__(" M2S Product Attachments"));
        return $resultPage;
    }
}
