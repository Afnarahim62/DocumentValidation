<?php

namespace Terrificminds\DocumentValidation\Model;

use Terrificminds\DocumentValidation\Api\Data\DocumentInterface as DocumentInt;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Document extends AbstractModel implements DocumentInt, IdentityInterface
{
    /**
     * cache tag
     */
    const CACHE_TAG = 'orderattachment_attachment';

    /**
     * @var string
     */
    protected $_cacheTag = 'orderattachment_attachment';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'orderattachment_attachment';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Terrificminds\DocumentValidation\Model\ResourceModel\Document');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get id
     *
     * Return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * Get order_id
     *
     * Return int
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Get quote_id
     *
     * Return int
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * Get file_name
     *
     * Return string
     */
    public function getFileName()
    {
        return $this->getData(self::FILE_NAME);
    }

    /**
     * Get Message
     *
     * Return string
     */
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * Get if_verified
     *
     * Return int
     */
    public function getIsVerified()
    {
        return $this->getData(self::IS_VERIFIED);
    }

    /**
     * Get Created_at
     *
     * Return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Get Updated_at
     *
     * Return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Get path
     *
     * Return string
     */
    public function getPath()
    {
        return $this->getData(self::PATH);
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId($orderid)
    {
        return $this->setData(self::ORDER_ID, $orderid);
    }

    /**
     * @inheritDoc
     */
    public function setQuoteId($quoteid)
    {
        return $this->setData(self::QUOTE_ID, $quoteid);
    }
    /**
     * @inheritDoc
     */
    public function setFileName($filename)
    {
        return $this->setData(self::FILE_NAME, $filename);
    }

    /**
     * @inheritDoc
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * @inheritDoc
     */
    public function setIsVerified($isverified)
    {
        return $this->setData(self::IS_VERIFIED, $isverified);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdat)
    {
        return $this->setData(self::CREATED_AT, $createdat);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedat)
    {
        return $this->setData(self::UPDATED_AT, $updatedat);
    }

    /**
     * SetPath  function
     *
     * @param string $path
     * @return string
     */
    public function setPath($path)
    {
        return $this->setData(self::PATH, $path);
    }

    /**
     * GetDefaultValues function
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }
}
