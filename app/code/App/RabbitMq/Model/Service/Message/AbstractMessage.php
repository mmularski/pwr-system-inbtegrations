<?php

namespace App\RabbitMq\Model\Service\Message;

use App\RabbitMq\Model\Service\AbstractElement;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AbstractMessage
 */
abstract class AbstractMessage extends AbstractElement
{
    /**
     * Message id property name
     */
    const PROPERTY_MESSAGE_ID = 'message_id';

    /**
     * @var null|AMQPMessage
     */
    protected $message;

    /**
     * Creates AMQPMessage object
     *
     * @param string $data
     *
     * @return $this
     */
    public function createMessage($data = '')
    {
        $message = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        // set message id
        $message->set(self::PROPERTY_MESSAGE_ID, uniqid($this->getService()::SERVICE_NAME));

        $this->message = $message;

        return $this;
    }

    /**
     * Returns AMQP message id
     *
     * @param null $message
     *
     * @return mixed|\PhpAmqpLib\Channel\AMQPChannel|string
     */
    public function getAMQPMsgId($message = null)
    {
        if ($message instanceof AMQPMessage) {
            return $message->get(self::PROPERTY_MESSAGE_ID);
        }

        if ($this->message instanceof AMQPMessage) {
            return $this->message->get(self::PROPERTY_MESSAGE_ID);
        }

        return '';
    }

    /**
     * @return null|AMQPMessage
     */
    public function getAMQPMessage()
    {
        if (!$this->message instanceof AMQPMessage) {
            $this->createMessage();
        }

        return $this->message;
    }

    /**
     * @param AMQPMessage $message
     *
     * @return $this
     */
    public function setAMQPMessage(AMQPMessage $message)
    {
        $this->message = $message;

        return $this;
    }
}
