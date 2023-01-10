<?php

namespace Terrificminds\DocumentValidation\Controller\Document;

use Terrificminds\DocumentValidation\Model\Save\SaveToTable;

class Uploade extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Terrificminds\DocumentValidation\Model\Save\SaveToTable
     */
    protected $documentHelper;
    /**
     * Undocumented function
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param Document $documentHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Terrificminds\DocumentValidation\Model\Save\SaveToTable $documentHelper
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->documentHelper = $documentHelper;
    }

    /**
     * Save the documents function
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $result = $this->documentHelper->saveAttachment($this->getRequest());
        $response = $this->resultRawFactory->create()
        ->setHeader('Content-type', 'text/plain')
        ->setContents(json_encode($result));
        return $response;
    }
}
