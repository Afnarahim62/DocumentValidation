<?php

namespace Terrificminds\DocumentValidation\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Terrificminds\DocumentValidation\Model\ResourceModel\Document\Collection;

class DocumentOrderAfterPlaceObserver implements ObserverInterface
{
    /**
     * Document Collection variable
     *
     * @var Terrificminds\DocumentValidation\Model\ResourceModel\Document\Collection
     */
    protected $documentCollection;

    /**
     * Construct function
     *
     * @param Collection $documentCollection
     */
    public function __construct(
        Collection $documentCollection
    ) {
        $this->documentCollection = $documentCollection;
    }

    /**
     * Execute function
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order) {
            return $this;
        }
        $order->setData('document_added', "yes");
        $attachments = $this->documentCollection
            ->addFieldToFilter('quote_id', $order->getQuoteId())
            ->addFieldToFilter('order_id', ['is' => new \Zend_Db_Expr('null')]);

        foreach ($attachments as $attachment) {
            try {
                $attachment->setOrderId($order->getId())->save();
                $order->hold()->save();
            } catch (\Exception $e) {
                continue;
            }
        }

        return $this;
    }
}
