<?php

namespace App\RabbitMq\Model\Service\Message;

use App\RabbitMq\Model\Service\AbstractElement;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire;

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
     * Application headers property name
     */
    const PROPERTY_APPLICATION_HEADERS = 'application_headers';

    /**
     * @var string $msgData
     */
    protected $msgData;

    /**
     * @var int $deliveryMode
     */
    protected $deliveryMode = AMQPMessage::DELIVERY_MODE_PERSISTENT;

    /**
     * @var null|AMQPMessage
     */
    protected $message;

    /**
     * @param array|string $msgData
     *
     * @return $this
     */
    public function setMsgData($msgData)
    {
        $this->msgData = $msgData;

        return $this;
    }

    /**
     * @return string
     */
    public function getMsgData()
    {
        return $this->msgData;
    }

    /**
     * @param int $mode
     *
     * @return $this
     */
    public function setDeliveryMode($mode)
    {
        $this->deliveryMode = $mode;

        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryMode()
    {
        return $this->deliveryMode;
    }

    /**
     * Creates AMQPMessage object
     *
     * @param string $data
     *
     * @return $this
     */
    public function createMessage($data = '')
    {
        $this->setMsgData($data);
        $message = new AMQPMessage(
            $this->getMsgData(),
            [
                'delivery_mode' => $this->deliveryMode,
            ]
        );

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
