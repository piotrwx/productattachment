<?php

namespace M2S\ProductAttachment\Controller\Adminhtml\Attachment;

use M2S\ProductAttachment\Model\Item;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    protected $itemModel;

    protected $pageFactory;

    protected $registry;

    public function __construct(
        Item $itemModel,
        PageFactory $pageFactory,
        Registry $registry,
        Action\Context $context
    ) {
        $this->registry = $registry;
        $this->itemModel = $itemModel;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->itemModel;
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This attachment not exist!'));
                $result = $this->resultRedirectFactory->create();
                return $result->setPath('m2s/attachment/index');
            }
        }
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->registry->register('id', $model);

        /**
         * @var \Magento\Framework\View\Result\Page $resultPage
         */
        $resultPage = $this->pageFactory->create();

        $title = $id ? __('Edit Attachment') : __('New');

        $resultPage->addBreadcrumb($title, $title);

        if ($id) {
            $resultPage->getConfig()->getTitle()->prepend('Edit');
        } else {
            $resultPage->getConfig()->getTitle()->prepend('New attachment');
        }

        return $resultPage;
    }
}
