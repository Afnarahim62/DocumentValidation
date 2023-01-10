<?php

namespace Terrificminds\DocumentValidation\Controller\Adminhtml\Document;

use Magento\Backend\App\Action;
use Terrificminds\DocumentValidation\Model\ResourceModel\Document\Collection;
use Magento\Framework\Controller\Result\RawFactory;
use Terrificminds\DocumentValidation\Model\ResourceModel\Document;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\CreditmemoSender;
use Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader;
use Terrificminds\DocumentValidation\Helper\Data;

// use Terrificminds\DocumentValidation\Model\Mail\TransportBuilder;

class Actions extends \Magento\Backend\App\Action
{
    protected $helperdatamail;



    /**
     * Salesorder variable
     *
     * @var Magento\Sales\Model\Order
     */
    protected $salesOrder;

    /**
     * OrderRepository variable
     *
     * @var Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * Document variable
     *
     * @var Terrificminds\DocumentValidation\Model\ResourceModel\Document
     */
    protected $document;

    /**
     * @var \Magento\Framework\Registry
     */
    private $_coreRegistry;

    /**
     * DocumentCollection variable
     *
     * @var Terrificminds\DocumentValidation\Model\ResourceModel\Document\Collection
     */
    protected $documentCollection;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * CreditmemoSender variable
     *
     * @var Magento\Sales\Model\Order\Email\Sender\CreditmemoSender
     */
    protected $creditmemoSender;

    /**
     * CreditmemoLOader variable
     *
     * @var Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader
     */
    protected $creditmemoLoader;

    /**
     * Construct function
     *
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param Collection $documentCollection
     * @param RawFactory $resultRawFactory
     * @param Document $document
     * @param OrderRepository $orderRepository
     * @param Order $salesOrder
     * @param CreditmemoSender $creditmemoSender
     * @param CreditmemoLoader $creditmemoLoader
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        Collection $documentCollection,
        RawFactory $resultRawFactory,
        Document $document,
        OrderRepository $orderRepository,
        Order $salesOrder,
        CreditmemoSender $creditmemoSender,
        CreditmemoLoader $creditmemoLoader,
        Data $helperdatamail

    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
        $this->documentCollection = $documentCollection;
        $this->resultRawFactory = $resultRawFactory;
        $this->document = $document;
        $this->orderRepository = $orderRepository;
        $this->salesOrder = $salesOrder;
        $this->creditmemoSender = $creditmemoSender;
        $this->creditmemoLoader = $creditmemoLoader;

        $this->helperdatamail = $helperdatamail;

    }

    /**
     * Execute function
     *
     * @return void
     */
    public function execute()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
           $logger = new \Zend_Log();
           $logger->addWriter($writer);
           $logger->info("/////////////////-----logger initiated-----//////////////////////");
           $logger->info("1 " . print_r("helooo", true));

        $result = $this->getRequest()->getPostValue();
        if($this->helperdatamail->sendMail()) {
            $logger->info("Aloowed extension " . print_r("mail send", true));
        } else {
            $logger->info("Aloowed extension " . print_r("mai not send", true));
        }
        $logger->info("2 " . print_r("helooo", true));
        $attachments = $this->documentCollection
            ->addFieldToFilter('order_id', $result['orderid'])
            ->addFieldToFilter('is_verified', ['is' => new \Zend_Db_Expr('null')]);
        $id = $result['id_start'];
        foreach ($attachments as $attachment) {
            try {
                $attachment->setIsVerified($result[$id])->save();
                $id = $id + 1;
            } catch (\Exception $e) {
                continue;
            }
        }
        $DocumentsAdded = $this->getAction($result['orderid']);
        $currentOrder = $this->orderRepository->get($result['orderid']);
        $item_data = [];
        foreach ($currentOrder->getAllVisibleItems() as $item) {
            $item_data['item_id'] = $item->getItemId();
        }

        if (in_array("reject", $DocumentsAdded) === false) {
            if ($currentOrder->canUnhold()) {
                    $currentOrder->unhold()->save();
            }
        } else {
            $currentOrder->unhold()->save();
            $status = $currentOrder->getStatus();
            if ($status == "processing") {
                $this->creditMemo($currentOrder['entity_id'], $item_data);
            } elseif ($status == "pending") {
                $currentOrder->cancel()->save();
            }
        }

       
        $response = $this->resultRawFactory->create()
        ->setContents(json_encode($DocumentsAdded));
        return $response;
    }

    /**
     * Get action of the documents
     *
     * @param int $entityId
     * @return array
     */
    public function getAction($entityId)
    {
        $DocumentsAdded = $this->document->getDocumentByOrder($entityId);
        $action = [];
        foreach ($DocumentsAdded as $key => $data) {
            $action[] = $data['is_verified'];
        }
        return $action;
    }

    /**
     * Credit memo function
     *
     * @param int $orderId
     * @param array $item_data
     * @return void
     */
    public function creditMemo($orderId, $item_data)
    {
        $creditMemoData = [];
        $creditMemoData['do_offline'] = 1;
        $creditMemoData['shipping_amount'] = 0;
        $creditMemoData['adjustment_positive'] = 0;
        $creditMemoData['adjustment_negative'] = 0;
        $creditMemoData['comment_text'] = 'comment_text_for_creditmemo';
        $creditMemoData['send_email'] = 1;
        //$creditMemoData['refund_customerbalance_return_enable'] = 0; // for Magento commerce
        $orderItemId = $item_data['item_id']; // pass order item id
        $itemToCredit[$orderItemId] = ['qtys' => 1];
        $creditMemoData['items'] = $itemToCredit;
        try {
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->creditmemoLoader->setOrderId($orderId); //pass order id
            $this->creditmemoLoader->setCreditmemo($creditMemoData);
            $creditmemo = $this->creditmemoLoader->load();

            if ($creditmemo) {
                if (!$creditmemo->isValidGrandTotal()) {
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('The credit memo\'s total must be positive.')
                    );
                }

                if (!empty($creditMemoData['comment_text'])) {
                    $creditmemo->addComment(
                        $creditMemoData['comment_text'],
                        isset($creditMemoData['comment_customer_notify']),
                        isset($creditMemoData['is_visible_on_front'])
                    );
                    $creditmemo->setCustomerNote($creditMemoData['comment_text']);
                    $creditmemo->setCustomerNoteNotify(isset($creditMemoData['comment_customer_notify']));
                }
                $creditmemoManagement = $this->_objectManager->create(
                    \Magento\Sales\Api\CreditmemoManagementInterface::class
                );
                $creditmemo->getOrder()->setCustomerNoteNotify(!empty($creditMemoData['send_email']));
                $creditmemoManagement->refund($creditmemo, (bool)$creditMemoData['do_offline']);

                if (!empty($creditMemoData['send_email'])) {
                    $this->creditmemoSender->send($creditmemo);
                }

                $this->messageManager->addSuccessMessage(__('You created the credit memo.'));
                $this->_getSession()->getCommentText(true);
                $resultRedirect->setPath('sales/order/view', ['order_id' => $orderId]);
                return $resultRedirect;
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            $this->messageManager->addErrorMessage(__('We can\'t save the credit memo right now.'));
        }
        $this->_redirect('adminhtml/*/');
    }

    

}
