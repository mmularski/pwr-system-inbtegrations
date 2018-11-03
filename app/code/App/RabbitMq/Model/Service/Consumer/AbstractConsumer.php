<?php

namespace App\RabbitMq\Model\Service\Consumer;

use App\RabbitMq\Model\Service\AbstractService;
use App\RabbitMq\Model\Service\AbstractElement;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AbstractConsumer
 */
abstract class AbstractConsumer extends AbstractElement implements ConsumerInterface
{
    /**
     * Consumer callback method
     *
     * @param AMQPMessage $message
     *
     * @return void;
     */
    public function callback(AMQPMessage $message)
    {
        $msgId = $this->getService()->getMessage()->getAMQPMsgId($message);

        $this->getService()->logger->info(sprintf('Message: %s callback.', $msgId));

        //Add acknowledgment - remove message from queue
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        $this->getService()->logger->info('Acknowledgement added for message: ' . $msgId);
    }

    /**
     * Consume messages from channel
     *
     * @return int
     * @throws \Exception
     */
    public function consume()
    {
        $this->getService()->getChannel()->basic_qos(
            null,
            1, //limit of unacknowledged messages per queue
            null
        );

        $this->getService()->declareQueue();

        $this->getService()->getChannel()->basic_consume(
            $this->getService()->getQueue()->getName(),
            '',
            false,
            false,
            false,
            false,
            [
                $this,
                'callback',
            ]
        );

        $counter = count($this->getService()->getChannel()->callbacks);

        while ($counter > 0) {
            try {
                $counter--;

                $this->getService()->getChannel()->wait();
            } catch (\Exception $e) {
                $this->getService()->logger->error($e->getMessage());

                return AbstractService::EXIT_CODE_ERROR;
            }
        }

        $this->getService()->closeChannel();
        $this->getService()->closeConnection();

        return AbstractService::EXIT_CODE_SUCCESS;
    }
}
