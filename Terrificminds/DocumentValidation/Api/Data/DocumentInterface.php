<?php

namespace Terrificminds\DocumentValidation\Api\Data;

interface DocumentInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    public const ID = 'id';
    public const ORDER_ID = 'order_id';
    public const QUOTE_ID = 'quote_id';
    public const FILE_NAME = 'file_name';
    public const MESSAGE = 'message';
    public const IS_VERIFIED = 'is_verified';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const PATH = 'file_path';
    public const ATTRIBUTES = [
        self::ID,
        self::ORDER_ID,
        self::QUOTE_ID,
        self::FILE_NAME,
        self::MESSAGE,
        self::IS_VERIFIED,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::PATH,
    ];
    /**
     *  Id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Order id
     *
     * @return int|null
     */
    public function getOrderId();

    /**
     * Quote id
     *
     * @return int|null
     */
    public function getQuoteId();
    /**
     * File name
     *
     * @return string|null
     */
    public function getFileName();

    /**
     * Message
     *
     * @return string|null
     */
    public function getMessage();

    /**
     * Is Verified
     *
     * @return int|null
     */
    public function getIsVerified();

    /**
     * Created at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Updated at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Path
     *
     * @return string|null
     */
    public function getPath();

    /**
     * Set  id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set entity id
     *
     * @param int $orderid
     * @return $this
     */
    public function setOrderId($orderid);

    /**
     * Set quote id
     *
     * @param int $quoteid
     * @return $this
     */
    public function setQuoteId($quoteid);
    /**
     * Set File Name
     *
     * @param string $filename
     * @return $this
     */
    public function setFileName($filename);

    /**
     * Set Message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * Set is verified
     *
     * @param int $isverified
     * @return $this
     */
    public function setIsVerified($isverified);

    /**
     * Set created at
     *
     * @param string $createdat
     * @return $this
     */
    public function setCreatedAt($createdat);

    /**
     * Set updated at
     *
     * @param string $updatedat
     * @return $this
     */
    public function setUpdatedAt($updatedat);

    /**
     * Set file path
     *
     * @param string $path
     * @return $this
     */
    public function setPath($path);
}
