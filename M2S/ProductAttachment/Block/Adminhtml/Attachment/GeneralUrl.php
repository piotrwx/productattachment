<?php

namespace M2S\ProductAttachment\Block\Adminhtml\Attachment;

use M2S\ProductAttachment\Model\Item;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class GeneralUrl
{
    protected $registry;

    protected $urlBuilder;

    public function __construct(
        Context $context,
        Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }
    public function getId()
    {
        /** @var Item $attachment */
        $attachment = $this->registry->registry('index');
        return $attachment ? $attachment->getId() : null;
    }

    public function getUrl($route='', $param=[]): string
    {
        return $this->urlBuilder->getUrl($route, $param);
    }
}
