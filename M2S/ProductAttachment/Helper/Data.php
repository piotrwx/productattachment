<?php

namespace M2S\ProductAttachment\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends \Magento\Framework\Url\Helper\Data
{
    const XML_PATH_ENABLED = 'm2s/productattachment/enabled';
    const XML_PATH_ADD_ATTACHMENT_BY_CUSTOMER = 'm2s/productattachment/addbycustomer';

    protected $_product = null;

    protected $_coreRegistry = null;

    private $_storeManager;

    private $config;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ScopeConfigInterface $config,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->config = $config;
        $this->_coreRegistry = $coreRegistry;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getProduct()
    {
        if ($this->_product !== null) {
            return $this->_product;
        }
        return $this->_coreRegistry->registry('product');
    }

    public function getActionUrl()
    {
        return $this->_getUrl(
            'm2s/add/add',
            [
                'product_id' => $this->getProduct()->getId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED => $this->getEncodedUrl()
            ]
        );
    }

    public function isEnabled()
    {
        return $this->config->getValue(self::XML_PATH_ENABLED);
    }

    public function isEnabledCustomerAttachment()
    {
        return $this->config->getValue(self::XML_PATH_ADD_ATTACHMENT_BY_CUSTOMER);
    }
}
