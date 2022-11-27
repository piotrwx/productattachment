<?php

declare(strict_types=1);

namespace M2S\ProductAttachment\Block\Adminhtml\Attachment;

use M2S\ProductAttachment\Model\Item;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

class GeneralUrl
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        Context $context,
        Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        /** @var Item $attachment */
        $attachment = $this->registry->registry('index');
        return $attachment->getId();
    }

    /**
     * @param string $route
     * @param array $param
     * @return string
     */
    public function getUrl(string $route = '', array $param = []): string
    {
        return $this->urlBuilder->getUrl($route, $param);
    }
}
