<?php

namespace M2S\ProductAttachment\Controller\Adminhtml\Attachment;

use M2S\ProductAttachment\Model\ItemFactory;
use Magento\Backend\App\Action;

class Save extends Action
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
        $request = $this->getRequest()->getPostValue()['general'];
//
//                var_dump($this->getRequest()->getPostValue()['general']);
//        die();

        if (!isset($request['id'])) {
            $attachmentArray = $request['attachment_path']['0'];
            if ($attachmentArray['type'] == 'application/pdf') {
                $request['image'] = $this->getViewFileUrl('M2S_ProductAttachment::images/pdf.png');
            } else {
                $request['image'] = $attachmentArray['url'];
            }

            $this->itemFactory->create()
                ->setData(
                    [

                        'product_sku' => $request['product_sku'],
                        'status' => $request['status'],
                        'image' => $request['image'],
                        'attachment_path' => $attachmentArray['url'],
                        'attachment_type' => $attachmentArray['type'],
                        'file_name' => $attachmentArray['name']
                    ]
                )
                ->save();
        } else {
            $this->itemFactory->create()
                ->setData(
                    [
                        'id' => $request['id'],
                        'product_sku' => $request['product_sku'],
                        'status' => $request['status']
                    ]
                )
                ->save();
        }

        return $this->resultRedirectFactory->create()->setPath('m2s/attachment/index');
    }
}
