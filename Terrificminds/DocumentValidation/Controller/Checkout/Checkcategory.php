<?php

namespace Terrificminds\DocumentValidation\Controller\Checkout;

use Terrificminds\DocumentValidation\Helper\Data;
use Magento\Framework\Controller\Result\JsonFactory;

class Checkcategory extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $_jsonHelper;
    /**
     * $helperData variable
     *
     * @var Terrificminds\DocumentValidation\Helper\Data
     */
    protected $helperData;

    /**
     * Category variable
     *
     * @var Magento\Catalog\Model\Category
     */
    protected $category;
   /**
    * @var \Magento\Checkout\Model\Session
    */
    protected $checkoutSession;

    /**
     * Construct function
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Model\Category $category
     * @param Data $helperData
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Model\Category $category,
        Data $helperData,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->category = $category;
        $this->helperData = $helperData;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Execute function
     *
     * @return array
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $Configvalue = $this->helperData->getConfigValue('allowed_categories');
        $ConfigEnable = $this->helperData->getConfigValue('enable');
        $post = $this->getRequest()->getPostValue();
        $quote = $this->checkoutSession->getQuote();
        foreach ($quote->getAllVisibleItems() as $item) {
             $categoryIds = $item->getProduct()->getCategoryIds();
        }
        if (in_array($Configvalue, $categoryIds) && $ConfigEnable == 1) {
            $response['success'] = true;
            return $resultJson->setData($response);
        } else {
            $response['success'] = false;
            return $resultJson->setData($response);
        }
    }
}
