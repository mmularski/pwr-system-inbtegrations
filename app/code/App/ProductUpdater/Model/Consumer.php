<?php

namespace App\ProductUpdater\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use PhpAmqpLib\Message\AMQPMessage;
use App\RabbitMq\Model\Service\Consumer\AbstractConsumer;
use App\RabbitMq\Model\Service\Consumer\ConsumerInterface;

/**
 * Class Consumer.
 */
class Consumer extends AbstractConsumer implements ConsumerInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var State
     */
    private $state;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * Consumer constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ProductRepositoryInterface $productRepository
     * @param State $state
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ProductRepositoryInterface $productRepository,
        State $state,
        StockRegistryInterface $stockRegistry
    ) {
        parent::__construct($context, $registry);

        $this->productRepository = $productRepository;
        $this->state = $state;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * Callback method for message consumer
     *
     * @param AMQPMessage $message
     *
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function callback(AMQPMessage $message)
    {
        try {
            $this->state->setAreaCode(Area::AREA_ADMINHTML);
        } catch (\Exception $ex) {
            //Do nothing. Area code is set
        }

        $messageBody = json_decode($message->getBody(), true);
        $product = $this->productRepository->get($messageBody['productSku']);

        $logInfo = [
            'Message ' . $this->getService()->getMessage()->getAMQPMsgId($message) . ' callback invoked.',
            'Message body: ' . serialize($messageBody),
            'Product stock quantity before change: ' . $product->getExtensionAttributes()->getStockItem()->getQty(),
            'Product stock quantity after change: ' .
            ($product->getExtensionAttributes()->getStockItem()->getQty() - $messageBody['diff']),
        ];

        $stockItem = $this->stockRegistry->getStockItemBySku($messageBody['productSku']);
        $stockItem->setQty($stockItem->getQty() - (int) $messageBody['diff']);
        $this->stockRegistry->updateStockItemBySku($messageBody['productSku'], $stockItem);

        $this->getService()->logger->info(
            implode(
                PHP_EOL,
                $logInfo
            )
        );

        // add message acknowledgement to remove it from queue after successful processing
        parent::callback($message);
    }
}
