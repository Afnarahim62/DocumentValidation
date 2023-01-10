<?php

namespace Terrificminds\DocumentValidation\Model\ResourceModel\Document;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Terrificminds\DocumentValidation\Model\Document', 'Terrificminds\DocumentValidation\Model\ResourceModel\Document');
    }
}
