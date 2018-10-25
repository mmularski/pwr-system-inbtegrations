<?php

namespace App\RabbitMq\Model\Service\Consumer;

use App\RabbitMq\Model\Service\AbstractService;
use App\RabbitMq\Model\Service\AbstractElement;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use App\RabbitMq\Model\Service\Message\AbstractMessage;

/**
 * Class AbstractConsumer
 */
abstract class AbstractConsumer extends AbstractElement implements ConsumerInterface
{
    /**
     * Sets prefetch count for the channel
     *
     * @return void;
     */
    public function basicQos()
    {
        $this->getService()->getChannel()->basic_qos(
            null,
            5, //limit of unacknowledged messages per queue
            null
        );
    }

    /**
     * Consumer callback method
     *
     * @param AMQPMessage $message
     *
     * @return mixed;
     */
    public function callback(AMQPMessage $message)
    {
        $msgId = $this->getService()->getMessage()->getAMQPMsgId($message);

        $this->getService()->logger->info(sprintf('Message: %s callback.', $msgId));

        //Add acknowledgment - remove message from queue
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        $this->getService()->logger->info(
            'Acknowledgement added for message: ' . $msgId
        );

    }

    /**
     * Consume messages from channel
     *
     * @return int
     * @throws \Exception
     */
    public function consume()
    {
        $this->basicQos();
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

        while (count($this->getService()->getChannel()->callbacks)) {
            try {
                $this->getService()->getChannel()->wait(
                    null,
                    true,
                    1
                );
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
