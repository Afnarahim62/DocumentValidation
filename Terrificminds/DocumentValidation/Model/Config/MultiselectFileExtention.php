<?php
 
namespace Terrificminds\DocumentValidation\Model\Config;
 
class MultiselectFileExtention implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Give the options of file extention
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => "pdf", 'label' => __('Pdf')],
            ['value' => "png", 'label' => __('png')],
            ['value' => "jpg", 'label' => __('jpg')]
        ];
    }
}
