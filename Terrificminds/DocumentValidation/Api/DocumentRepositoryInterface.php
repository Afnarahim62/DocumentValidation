<?php

namespace Terrificminds\DocumentValidation\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Terrificminds\DocumentValidation\Api\Data\DocumentInterface;

interface DocumentRepositoryInterface
{
   /**
    * To get id
    *
    * @param int $id
    * @return DocumentInterface $attachment
    * @throws \Magento\Framework\Exception\NoSuchEntityException
    */
    public function getById($id);

    /**
     * To save
     *
     * @param DocumentInterface $attachment
     * @return void
     */
    public function save(DocumentInterface $attachment);

    /**
     * To get list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Terrificminds\DocumentValidation\Api\Data\DocumentSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * To delete
     *
     * @param \Terrificminds\DocumentValidation\Api\Data\DocumentInterface $attachment
     * @return void
     */
    public function delete(DocumentInterface $attachment);

    /**
     * Delete by id function
     *
     * @param int $id
     * @return void
     */
    public function deleteById($id);
}
