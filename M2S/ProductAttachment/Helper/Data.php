<?php

declare(strict_types=1);

namespace M2S\ProductAttachment\Helper;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Registry;
use Magento\Framework\Url\Helper\Data as UrlHelperData;
use Magento\Store\Model\ScopeInterface;

class Data extends UrlHelperData
{
    const XML_PATH_ENABLED = 'm2s/productattachment/enabled';
    const XML_PATH_ADD_ATTACHMENT_BY_CUSTOMER = 'm2s/productattachment/addbycustomer';

    /**
     * @var mixed|null
     */
    protected $product = null;

    /**
     * @var Registry|null
     */
    protected $coreRegistry = null;

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $config
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $config,
        Registry $coreRegistry
    ) {
        $this->config = $config;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * @return mixed|null
     */
    public function getProduct(): mixed
    {
        if ($this->product !== null) {
            return $this->product;
        }
        return $this->coreRegistry->registry('product');
    }

    /**
     * @return string
     */
    public function getActionUrl(): string
    {
        return $this->_getUrl(
            'm2s/add/add',
            [
                'product_id' => $this->getProduct()->getId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->getEncodedUrl()
            ]
        );
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->config->getValue(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isEnabledCustomerAttachment(): bool
    {
        return (bool) $this->config->getValue(self::XML_PATH_ADD_ATTACHMENT_BY_CUSTOMER, ScopeInterface::SCOPE_STORE);
    }
}
