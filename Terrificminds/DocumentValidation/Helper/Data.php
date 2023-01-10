<?php

namespace Terrificminds\DocumentValidation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
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
        \Magento\Framework\Json\EncoderInterface $jsonEncoder
    ) {
        parent::__construct($context);
        $this->jsonEncoder = $jsonEncoder;
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
}

