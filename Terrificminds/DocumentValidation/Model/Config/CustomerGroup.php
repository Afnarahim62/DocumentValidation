<?php

namespace Terrificminds\DocumentValidation\Model\Config;

class CustomerGroup implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     *
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_customerGroupColl;
        
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroupColl
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroupColl,
        array $data = []
    ) {
        $this->_customerGroupColl = $customerGroupColl;
        $this->context = $context;
    }
    /**
     * Get the options of customer group
     *
     * @return array
     */
    public function toOptionArray()
    {
            $customerGroups = $this->_customerGroupColl->toOptionArray();
            return [
                ['value' => 0, 'label' => __($customerGroups[0]['label'])],
                ['value' => 1, 'label' => __($customerGroups[1]['label'])],
                ['value' => 2, 'label' => __($customerGroups[2]['label'])],
                ['value' => 3, 'label' => __($customerGroups[3]['label'])]
            ];
    }
}
