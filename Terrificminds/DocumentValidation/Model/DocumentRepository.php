<?php
namespace Terrificminds\DocumentValidation\Model;

use Terrificminds\DocumentValidation\Api\Data;
use Terrificminds\DocumentValidation\Api\DocumentRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Terrificminds\DocumentValidation\Model\ResourceModel\Document as ResourceDocument;
use Terrificminds\DocumentValidation\Model\ResourceModel\Document\CollectionFactory as DocumentCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Terrificminds\DocumentValidation\Api\Data\DocumentInterfaceFactory;

class DocumentRepository implements DocumentRepositoryInterface
{
    /**
     * @var ResourceDocument
     */
    protected $resource;

    /**
     * @var DocumentFactory
     */
    protected $documentFactory;

    /**
     * @var BlockCollectionFactory
     */
    protected $attachmentCollectionFactory;

    /**
     * @var Data\BlockSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Magento\Cms\Api\Data\BlockInterfaceFactory
     */
    protected $dataAttachmentFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

  /**
   * Undocumented function
   *
   * @param ResourceDocument $resource
   * @param DocumentFactory $documentFactory
   * @param Terrificminds\DocumentValidation\Api\Data\DocumentInterfaceFactory $dataAttachmentFactory
   * @param DocumentCollectionFactory $attachmentCollectionFactory
   * @param Data\AttachmentSearchResultsInterfaceFactory $searchResultsFactory
   * @param DataObjectHelper $dataObjectHelper
   * @param DataObjectProcessor $dataObjectProcessor
   * @param StoreManagerInterface $storeManager
   */
    public function __construct(
        ResourceDocument $resource,
        DocumentFactory $documentFactory,
        DocumentInterfaceFactory $dataAttachmentFactory,
        DocumentCollectionFactory $attachmentCollectionFactory,
        Data\DocumentSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->blockFactory = $documentFactory;
        $this->blockCollectionFactory = $attachmentCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBlockFactory = $dataAttachmentFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * To Save
     *
     * @param \Terrificminds\DocumentValidation\Api\Data\DocumentInterface $attachment
     * @return \Terrificminds\DocumentValidation\Api\Data\DocumentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\DocumentInterface $attachment)
    {
        try {
            $this->resource->save($attachment);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $attachment;
    }

    public function getById($id)
    {
        $attachment = $this->blockFactory->create();
        $this->resource->load($attachment, $id);
        if (!$attachment->getId()) {
            throw new NoSuchEntityException(__('CMS Block with id "%1" does not exist.', $id));
        }
        return $attachment;
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->blockCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $blocks = [];
        /** @var Block $blockModel */
        foreach ($collection as $blockModel) {
            $blockData = $this->dataBlockFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $blockData,
                $blockModel->getData(),
                'Terrificminds\DocumentValidation\Api\Data\DocumentInterface'
            );
            $blocks[] = $this->dataObjectProcessor->buildOutputDataArray(
                $blockData,
                'Terrificminds\DocumentValidation\Api\Data\DocumentInterface'
            );
        }
        $searchResults->setItems($blocks);
        return $searchResults;
    }

    public function delete(Data\DocumentInterface $attachment)
    {
        try {
            $this->resource->delete($attachment);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
