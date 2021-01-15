<?php

namespace M2S\ProductAttachment\Controller\Add;

use M2S\ProductAttachment\Controller\Add as AddController;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class Add extends AddController
{
    protected $_FILES = [];

    protected $productRepository;

    private $_uploaderFactory;

    private $storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\MediaStorage\Model\File\UploaderFactory $_uploaderFactory
    ) {
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->_uploaderFactory = $_uploaderFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $backUrl = $this->getRequest()->getParam(Action::PARAM_NAME_URL_ENCODED);
        $productId = (int)$this->getRequest()->getParam('product_id');
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$backUrl || !$productId) {
            $resultRedirect->setPath('/');
            return $resultRedirect;
        }
        $fileId = 'attachmentfile';
        try {
            $uploader = $this->_uploaderFactory->create(['fileId' => $fileId]);
            $uploader->setFilesDispersion(false);
            $uploader->setFilenamesCaseSensitivity(false);
            $uploader->setAllowRenameFiles(true);
            $uploader->setAllowedExtensions(['pdf','jpg','png']);
            $path='m2s/files';
            $result = $uploader->save('pub/media/' . $path);
            $pathToFile = $mediaUrl . $path . '/' . $result['file'];
            $product = $this->productRepository->getById($productId);
            $model = $this->_objectManager->create(\M2S\ProductAttachment\Model\Item::class)
                ->setProductSku($product->getSku())
                ->setAttachmentPath($pathToFile)
                ->setAttachmentType($result['type'])
                ->setFileName($result['name']);
            $model->save();
            $this->messageManager->addSuccessMessage(__('Your attachment is send to confirm by admin.'));
        } catch (NoSuchEntityException $noEntityException) {
            $this->messageManager->addErrorMessage(__('There are not enough parameters.'));
            $resultRedirect->setUrl($backUrl);
            return $resultRedirect;
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __("File not load, allowed file extension: jpg, pdf, png.")
            );
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

}
