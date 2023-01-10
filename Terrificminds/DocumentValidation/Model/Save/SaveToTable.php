<?php

namespace Terrificminds\DocumentValidation\Model\Save;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Filesystem;
use Magento\Framework\Escaper;
use Magento\Framework\Json\EncoderInterface;
use Terrificminds\DocumentValidation\Model\ResourceModel\Document\Collection;
use Terrificminds\DocumentValidation\Model\Save\Uploadfile;
use Terrificminds\DocumentValidation\Model\DocumentFactory;
use Terrificminds\DocumentValidation\Api\DocumentRepositoryInterface;
use Terrificminds\DocumentValidation\Api\Data\DocumentInterfaceFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class SaveToTable
{
    /**
     * @var \Terrificminds\DocumentValidation\Api\Data\DocumentInterfaceFactory
     */
    protected $documentInterfaceFactory;

    protected $documentRepositoryInterface;
    /**
     * @var \Terrificminds\DocumentValidation\Model\Save\Uploadfile
     */
    protected $uploadModel;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Terrificminds\DocumentValidation\Model\ResourceModel\Document\Collection
     */
    protected $documentCollection;

    /**
     * @var \Magento\Framework\Math\Random
     */
    protected $random;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;
    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    protected $documentFactory;
    /**
     * Undocumented function
     *
     * @param StoreManagerInterface $storeManager
     * @param Session $checkoutSession
     * @param DateTime $dateTime
     * @param Filesystem $fileSystem
     * @param Escaper $escaper
     * @param DocumentFactory $documentFactory
     * @param Uploadfile $uploadModel
     * @param Collection $documentCollection
     * @param EncoderInterface $jsonEncoder
     * @param DocumentRepositoryInterface $documentRepositoryInterface
     * @param DocumentInterfaceFactory $documentInterfaceFactory
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Session $checkoutSession,
        DateTime $dateTime,
        Filesystem $fileSystem,
        Escaper $escaper,
        DocumentFactory $documentFactory,
        Uploadfile $uploadModel,
        Collection $documentCollection,
        EncoderInterface $jsonEncoder,
        DocumentRepositoryInterface $documentRepositoryInterface,
        DocumentInterfaceFactory $documentInterfaceFactory
    ) {
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->dateTime = $dateTime;
        $this->fileSystem = $fileSystem;
        $this->escaper = $escaper;
        $this->documentFactory = $documentFactory;
        $this->uploadModel = $uploadModel;
        $this->documentCollection = $documentCollection;
        $this->jsonEncoder = $jsonEncoder;
        $this->documentRepositoryInterface = $documentRepositoryInterface;
        $this->documentInterfaceFactory = $documentInterfaceFactory;
    }

    /**
     * Upload file and save attachment
     * @param \Magento\Framework\App\Request\Http $request
     * @return array
     */
    public function saveAttachment($request)
    {
        try {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
           $logger = new \Zend_Log();
           $logger->addWriter($writer);
           $logger->info("/////////////////-----logger initiated-----//////////////////////");
           $logger->info("file upload " . print_r("helooo", true));


            $uploadData = $request->getFiles()->get('order-attachment')[0];
            $result = $this->uploadModel->uploadFileAndGetInfo($uploadData);
            $result['paths'] = $result['path'] ."/". $result['file'];
            $result['url'] = $this->storeManager->getStore()->getBaseUrl() . "pub/media/document/" . $result['file'];
            $date = $this->dateTime->gmtDate('Y-m-d H:i:s');
            $result['success'] = true;
            $attachment = $this->documentCollection;
            unset($result['tmp_name']);
             $attachment = $this->documentFactory->create()->setFileName($result['name'])->setPath($result['url'])
                 ->setCreatedAt($date)
                 ->setUpdatedAt($date);
            if ($orderId = $request->getParam('order_id')) {
                   $attachment-> setOrderId($orderId);
             } else {
                    $quote = $this->checkoutSession->getQuote();
                    $attachment->setQuoteId($quote->getId());
                } 
                $logger->info("file upload " . print_r($result['name'], true));
                $logger->info("file upload " . print_r($result['url'], true));
                $logger->info("file upload " . print_r($date, true));
                $logger->info("file upload " . print_r($quote->getId(), true));

             $attachment->save();
             $logger->info("file upload " . print_r("save in step2", true));
             $url = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . "document/" . $attachment->getPath();
             $result["quote_id"] = $attachment->getQuoteId();
             $result["order_id"] = $attachment->getOrderId();
             $result['url'] = $url;
             $result["uploaded_at"] = $attachment->getCreatedAt();
             $result["modified_at"] = $attachment->getUpdatedAt();
             $result['attachment_id'] = $attachment->getId();
             $result['comment'] = '';
        } catch (\Exception $e) {
            $result = [
                'success' => false,
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ];
        }
        $logger->info("file upload " . print_r($result, true));
        return $result;
    }
}
