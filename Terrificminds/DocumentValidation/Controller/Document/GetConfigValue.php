<?php

namespace Terrificminds\DocumentValidation\Controller\Document;

use Terrificminds\DocumentValidation\Helper\Data;

class GetConfigValue extends \Magento\Framework\App\Action\Action
{
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
     * Construct function
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param Data $getConfigValue
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        Data $getConfigValue
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->getConfigValue = $getConfigValue;
    }

    /**
     * Get Configuration Value function
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $result = $this->getRequest();
        $Configuration = $this->getConfigValue->getDocumentConfig();
        $response = $this->resultRawFactory->create()
        ->setContents(json_encode($Configuration));

        return $response;
    }
}
