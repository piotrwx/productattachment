<?php

declare(strict_types=1);

namespace M2S\ProductAttachment\Controller\Add;

use Exception;
use M2S\ProductAttachment\Controller\Add as AddController;
use M2S\ProductAttachment\Model\Item;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

class Add extends AddController
{
    /**
     * @var Repository
     */
    protected $_assetRepo;

    /**
     * @var array
     */
    protected $_FILES = [];

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Repository $assetRepo
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param UploaderFactory $_uploaderFactory
     */
    public function __construct(
        Repository $assetRepo,
        Context $context,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        UploaderFactory $_uploaderFactory
    ) {
        $this->_assetRepo = $assetRepo;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->uploaderFactory = $_uploaderFactory;
        parent::__construct($context);
    }

    /**
     * @return Redirect
     * @throws NoSuchEntityException
     */
    public function execute(): Redirect
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $backUrl = $this->getRequest()->getParam(Action::PARAM_NAME_URL_ENCODED);
        $productId = (int)$this->getRequest()->getParam('product_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$backUrl || !$productId) {
            $resultRedirect->setPath('/');
            return $resultRedirect;
        }
        $fileId = 'attachmentfile';
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
            $uploader->setFilesDispersion(false);
            $uploader->setFilenamesCaseSensitivity(false);
            $uploader->setAllowRenameFiles(true);
            $uploader->setAllowedExtensions(['pdf','jpg','png', 'jpeg']);
            $path='m2s/files';
            $result = $uploader->save('pub/media/' . $path);
            $pathToFile = $mediaUrl . $path . '/' . $result['file'];
            $image = $pathToFile;
            if ($result['type'] == 'application/pdf') {
                $image = $this->_assetRepo->getUrl("M2S_ProductAttachment::images/pdf.png");
            }
            $product = $this->productRepository->getById($productId);
            $model = $this->_objectManager->create(Item::class)
                ->setProductSku($product->getSku())
                ->setAttachmentPath($pathToFile)
                ->setImage($image)
                ->setAttachmentType($result['type'])
                ->setFileName($result['name']);
            $model->save();
            $this->messageManager->addSuccessMessage(__('Your attachment is send to confirm by admin.'));
        } catch (NoSuchEntityException $noEntityException) {
            $this->messageManager->addErrorMessage(__('There are not enough parameters.'));
            $resultRedirect->setUrl($backUrl);
            return $resultRedirect;
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __("File not load, allowed file extension: jpg, pdf, png.")
            );
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
