<?php

namespace Terrificminds\DocumentValidation\Block\Adminhtml\Order\View\Tab;

use Terrificminds\DocumentValidation\Model\ResourceModel\Document;

class DocumentAdded extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Template variable
     *
     * @var \Terrificminds\view\adminhtml\templates\order\view\tab\documentadded
     */
    protected $_template = 'Terrificminds_DocumentValidation::order/view/tab/documentadded.phtml';

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
     * Construct function
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param Document $document
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry             $registry,
        Document $document,
        array                                   $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
        $this->document = $document;

    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    /**
     * Retrieve order model instance
     *
     * @return int
     * Get current id order
     */
    public function getOrderId()
    {
        return $this->getOrder()->getEntityId();
    }

    /**
     * Retrieve order increment id
     *
     * @return string
     */
    public function getOrderIncrementId()
    {
        return $this->getOrder()->getIncrementId();
    }

    /**
     * Retrieve order increment id
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->getOrder()->getCustomerEmail();
    }

    /**
     * @inheritdoc
     */
    public function getTabLabel()
    {
        return __('Document Added');
    }

    /**
     * @inheritdoc
     */
    public function getTabTitle()
    {
        return __('Document Added');
    }

    /**
     * @inheritdoc
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isHidden()
    {
        $order = $this->getOrder();
        if ($order['document_added'] == "yes") {
            return false;
        } else {
            return true;
        }
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
