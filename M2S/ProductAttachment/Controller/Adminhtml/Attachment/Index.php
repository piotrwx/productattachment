<?php

declare(strict_types=1);

namespace M2S\ProductAttachment\Controller\Adminhtml\Attachment;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;


class Index implements HttpGetActionInterface
{
    private ResultFactory $resultFactory;

    /**
     * @param ResultFactory $resultFactory
     */
    public function __construct (
        ResultFactory $resultFactory
    ) {
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__(" M2S Product Attachments"));
        return $resultPage;
    }
}
