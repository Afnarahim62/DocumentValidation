<?php

namespace Terrificminds\DocumentValidation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;

use Magento\Checkout\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Data extends AbstractHelper
{
    protected $_checkoutSession;
    protected $customerRepository;

    const EMAIL_TEMPLATE = 'terrificminds/general/email_template';

    const EMAIL_SERVICE_ENABLE = 'terrificminds/general/enabled45';

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    protected const PATH = 'terrificminds/';

    /**
     * Construct function
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        LoggerInterface $logger,

        CustomerRepositoryInterface $customerRepository,
        Session $checkoutSession
    ) {
        parent::__construct($context);
        $this->jsonEncoder = $jsonEncoder;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->logger = $logger;

        $this->customerRepository = $customerRepository;
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * GetConfigValue function
     *
     * @param string $field
     * @return configuration value
     */
    public function getConfigValue($field)
    {
        return $this->scopeConfig->getValue(self::PATH . 'general/' . $field, ScopeInterface::SCOPE_STORE);
    }

    /**
     * GetFileExtention function
     *
     * @return string
     */
    public function getFileExtention()
    {
        return $this->getConfigValue('allowed_file_extension');
    }

    /**
     * Get file size
     *
     * @return string
     */
    public function getFileSize()
    {
        return $this->getConfigValue('max_file_size');
    }

    /**
     * Get the Configuration Value
     *
     * @return array
     */
    public function getDocumentConfig()
    {
        $attachSize = $this->getFileSize();
        $config = [
            'DocumentSize' => $this->getFileSize(),
            'DocumentExtention' => $this->getFileExtention(),
            'DocumentInvalidExt' => __('Invalid File Type'),
            'DocumentInvalidSize' => __('Size of the file is greather than allowed') . '(' . $attachSize . ' KB)'
        ];
        // return $this->jsonEncoder->encode($config);
        return $config;
    }

    /**
     * Send Mail
     *
     * @return $this
     *
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendMail()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
           $logger = new \Zend_Log();
           $logger->addWriter($writer);
           $logger->info("/////////////////-----logger initiated-----//////////////////////");


        $email = 'receiver@example.com'; //set receiver mail

        $this->inlineTranslation->suspend();
        $storeId = $this->getStoreId();

        /* email template */
        $template = $this->scopeConfig->getValue(
            self::EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $vars = [
            'message_1' => 'CUSTOM MESSAGE STR 1',
            'message_2' => 'custom message str 2',
            'store' => $this->getStore()
        ];

        // set from email
        $sender = $this->scopeConfig->getValue(
            'terrificminds/general/sender',
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );

        $transport = $this->transportBuilder->setTemplateIdentifier(
            $template
        )->setTemplateOptions(
            [
                'area' => Area::AREA_FRONTEND,
                'store' => $this->getStoreId()
            ]
        )->setTemplateVars(
            $vars
        )->setFromByScope(
            $sender
        )->addTo(
            $email
        )->addBcc(
           ['receiver1@example.com', 'receiver2@example.com']
        )->getTransport();
        try {
            $transport->sendMessage();
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
        $this->inlineTranslation->resume();

        return $this;
    }

        /*
     * get Current store id
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
        /*
     * get Current store Info
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }

    /**
     * Undocumented function
     *
     * @return object
     */
    public function getOrder()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
    }
}
