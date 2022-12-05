<?php

declare(strict_types=1);

namespace M2S\ProductAttachment\Controller\Add;

use Exception;
use M2S\ProductAttachment\Model\Item;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\Manager;

class Add implements ActionInterface
{
    /**
     * @var Repository
     */
    protected $assetRepo;

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
    private RequestInterface $request;
    private RedirectFactory $redirectFactory;
    private Item $item;
    private Manager $messageMenager;
    private RedirectInterface $redirect;
    private File $file;
    private DirectoryList $dir;

    /**
     * @param Repository $assetRepo
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(
        Repository $assetRepo,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        UploaderFactory $uploaderFactory,
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        Item $item,
        Manager $messageMenager,
        RedirectInterface $redirect,
        File $file,
        DirectoryList $dir,
    ) {
        $this->assetRepo = $assetRepo;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->uploaderFactory = $uploaderFactory;
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->item = $item;
        $this->messageMenager = $messageMenager;
        $this->redirect = $redirect;
        $this->file = $file;
        $this->dir = $dir;
    }

    /**
     * @return Redirect
     * @throws NoSuchEntityException
     */
    public function execute(): Redirect
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $backUrl = $this->request->getParam(ActionInterface::PARAM_NAME_URL_ENCODED);
        $productId = (int)$this->request->getParam('product_id');
        $resultRedirect = $this->redirectFactory->create();
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
            $path = 'm2s/files';

            $images = $this->dir->getPath('media'). '/' .$path;
            if ( ! file_exists($images)) {
                $this->file->mkdir($images);
            }
            $result = $uploader->save('pub/media/' . $path);

            $pathToFile = $mediaUrl . $path . '/' . $result['file'];
            $image = $pathToFile;
            if ($result['type'] == 'application/pdf') {
                $image = $this->assetRepo->getUrl("M2S_ProductAttachment::images/pdf.png");
            }
            $product = $this->productRepository->getById($productId);
            $model = $this->item
                ->setProductSku($product->getSku())
                ->setAttachmentPath($pathToFile)
                ->setImage($image)
                ->setAttachmentType($result['type'])
                ->setFileName($result['name']);
            $model->save();
            $this->messageMenager->addSuccessMessage(__('Your attachment is send to confirm by admin.'));
        } catch (NoSuchEntityException $noEntityException) {
            $this->messageMenager->addErrorMessage(__('There are not enough parameters.'));
            $resultRedirect->setUrl($backUrl);
            return $resultRedirect;
        } catch (Exception $e) {
            $this->messageMenager->addExceptionMessage(
                $e,
                __("File not load, allowed file extension: jpg, jpeg ,pdf, png.")
            );
        }
        $resultRedirect->setUrl($this->redirect->getRefererUrl());
        return $resultRedirect;
    }
}
