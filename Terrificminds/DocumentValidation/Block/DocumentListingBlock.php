<?php

namespace Terrificminds\DocumentValidation\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Page\Config;
use Magento\Checkout\Model\Session;
use Terrificminds\DocumentValidation\Model\ResourceModel\Document;

class DocumentListingBlock extends Template
{
   /**
    * @var \Magento\Framework\Registry
    */
    private $_coreRegistry;
    /**
     * Undocumented variable
     *
     * @var Terrificminds\DocumentValidation\Model\ResourceModel\Document
     */
    protected $document;

    /**
     * CheckoutSession variable
     *
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    public function __construct(
        Context $context,
        Config $pageConfig,
        array $data = [],
        Session $checkoutSession,
        Document $document,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context, $data);
        $this->pageConfig = $pageConfig;
        $this->checkoutSession = $checkoutSession;
        $this->document = $document;
        $this->_coreRegistry = $registry;
    }

    public function getCustomString()
    {
        return 'Custom Block';
    }

    /**
     * Get the specific order id function
     *
     * @return int
     */
    public function getOrderId()
    {
        $orderId = $this->getOrder()->getId();
        $entityId = $this->_coreRegistry->registry('current_order');
        return $orderId;
    }

    /**
     * Get the current order function
     *
     * @return 
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    /**
     * Get all the files function
     *
     * @return array
     */
    public function getDocumentList()
    {
        $entityId = $this->getOrderId();
        $DocumentsAdded = $this->document->getDocumentByOrder($entityId);
        return $DocumentsAdded;
    }

    
}
