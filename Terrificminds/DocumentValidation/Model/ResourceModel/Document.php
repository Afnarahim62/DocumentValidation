<?php

namespace Terrificminds\DocumentValidation\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Document extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init("document_verification","id");
    }

    public function getDocumentsByQuote($quoteId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable())
            ->where('quote_id = ?', $quoteId);

        return $connection->fetchAll($select);
    }

    public function getDocumentByOrder($orderId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable())
            ->where('order_id = ?', $orderId);
        return $connection->fetchAll($select);
    }

}
