<?php
/**
 * @package  App\ProductUpdater
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

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
     * @param bool        $skipQueue
     *
     * @return void
     */
    public function callback(AMQPMessage $message, $skipQueue = false)
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
        parent::callback($message, $skipQueue);
    }
}
