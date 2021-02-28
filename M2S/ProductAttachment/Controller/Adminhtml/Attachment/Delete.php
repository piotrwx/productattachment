<?php

namespace M2S\ProductAttachment\Controller\Adminhtml\Attachment;

use M2S\ProductAttachment\Model\Item as AttachemntModel;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;

class Delete extends Action
{
    protected $model;

    public function __construct(
        RedirectFactory $redirectFactory,
        PageFactory $pageFactory,
        AttachemntModel $attachmentModel,
        Action\Context $context
    ) {
        $this->resultRedirectFactory = $redirectFactory;
        $this->model = $attachmentModel;
        parent::__construct($context);
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('M2S_ProductAttachment::parent');
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model = $this->model->load($id);
            try {
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Attachment deleted'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            }
        }
        return $resultRedirect->setPath('*/*/index');
    }
}
