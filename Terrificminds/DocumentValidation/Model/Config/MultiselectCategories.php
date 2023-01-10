<?php

namespace Terrificminds\DocumentValidation\Model\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class MultiselectCategories implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    protected $_options;

    /**
     * StoreManger variable
     *
     * @var Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * CategoryCollection variable
     *
     * @var Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollection;

    /**
     * Context variable
     *
     * @var Magento\Backend\Block\Template\Context
     */
    protected $context;
     /**
      * Construct function
      *
      * @param \Magento\Backend\Block\Template\Context $context
      * @param \Magento\Store\Model\StoreManagerInterface $storeManager
      * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection
      * @param array $data
      */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CollectionFactory $categoryCollection,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_categoryCollection = $categoryCollection;
        $this->context = $context;
    }
    /**
     * Get the Categories of array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $this->_options = [];
        $categories = $this->_categoryCollection->create()->addAttributeToSelect('*')
        ->setStore($this->_storeManager->getStore());
        foreach ($categories as $categoryId => $category) {
            $this->_options[] = ['label' => $category->getName(), 'value' => $category->getId()];
        }
        $options = $this->_options;
        return $options;
    }
}
