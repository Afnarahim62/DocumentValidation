<?php

namespace Terrificminds\DocumentValidation\Controller\Document;

use Terrificminds\DocumentValidation\Helper\Data;
use Magento\Checkout\Model\Session;
use Terrificminds\DocumentValidation\Model\ResourceModel\Document;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\App\Action\Context;

class GetDocumentAdded extends \Magento\Framework\App\Action\Action
{
    /**
     * Undocumented variable
     *
     * @var Terrificminds\DocumentValidation\Model\ResourceModel\Document
     */
    protected $document;
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * Undocumented variable
     *
     * @var \Terrificminds\DocumentValidation\Helper\Data
     */
    protected $getConfigValue;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * Construct function
     *
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param Data $getConfigValue
     * @param Session $checkoutSession
     * @param Document $document
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        Data $getConfigValue,
        Session $checkoutSession,
        Document $document
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->getConfigValue = $getConfigValue;
        $this->checkoutSession = $checkoutSession;
        $this->document = $document;
    }

    /**
     * Get all the Documents function
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $result = $this->getRequest();
        $entityId = $this->checkoutSession->getQuote()->getId();
        $DocumentsAdded = $this->document->getDocumentsByQuote($entityId);
        $response = $this->resultRawFactory->create()
        ->setContents(json_encode($DocumentsAdded));

        return $response;
    }
}
