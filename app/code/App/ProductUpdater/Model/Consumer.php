<?php

namespace App\ProductUpdater\Model;

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
     * Consumer constructor.
     *
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(Context $context, Registry $registry)
    {
        parent::__construct($context, $registry);
    }

    /**
     * Callback method for message consumer
     *
     * @param AMQPMessage $message
     *
     * @return void
     */
    public function callback(AMQPMessage $message)
    {
        $logInfo = [
            'Message ' . $this->getService()->getMessage()->getAMQPMsgId($message) . ' callback invoked.',
            'Message body: ' . $message->getBody(),
        ];

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
