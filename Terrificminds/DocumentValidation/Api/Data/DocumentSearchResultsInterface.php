<?php

namespace Terrificminds\DocumentValidation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DocumentSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Undocumented function
     *
     * @return \Terrificminds\DocumentValidation\Api\Data\DocumentInterface[]
     */
    public function getItems();

    /**
     * Undocumented function
     *
     * @param array $items
     * @return \Terrificminds\DocumentValidation\Api\Data\DocumentInterface[] $items
     */
    public function setItems(array $items);
}
