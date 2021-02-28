<?php

namespace M2S\ProductAttachment\Controller\Adminhtml\Attachment;

use M2S\ProductAttachment\Controller\Add as AddController;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Controller\ResultFactory;

class FileUploader extends \Magento\Backend\App\Action
{
    protected $imageUploader;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \M2S\ProductAttachment\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        try {

            $result = $this->imageUploader->saveFileToTmpDir('attachment_path');


            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
