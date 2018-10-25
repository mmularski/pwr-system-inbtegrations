<?php
/**
 * @package   App\RabbitMq
 * @author    Wiktor Kaczorowski <wkaczorowski@App.pl>
 * @copyright 2016-2018 App Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

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
     * Message content type json
     */
    const CONTENT_TYPE_JSON = 'json';

    /**
     * Message content type XML
     */
    const CONTENT_TYPE_XML = 'xml';

    /**
     * Message id property name
     */
    const PROPERTY_MESSAGE_ID = 'message_id';

    /**
     * This message property is intended to store the number of message consumption attempts. After reaching the
     * upper limit of attempts, the message will be moved to trash (another queue or log file)
     */
    const PROPERTY_NUMBER_OF_ATTEMPTS = 'attempts_no';

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
     * @var string $contentType
     */
    protected $contentType;

    /**
     * @var array $availableContentTypes
     */
    protected $availableContentTypes = [
        self::CONTENT_TYPE_JSON,
        self::CONTENT_TYPE_XML,
    ];

    /**
     * Sets message content type
     *
     * @param string $contentType
     *
     * @return $this
     * @throws \Exception
     */
    public function setContentType($contentType)
    {
        if (in_array(
            $contentType,
            $this->availableContentTypes
        )) {
            $this->contentType = $contentType;

            return $this;
        }

        throw new \Exception(
            'Incorrect message content type: ',
            (string) $contentType
        );
    }

    /**
     * Returns message content type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

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
        $message->set(
            self::PROPERTY_MESSAGE_ID,
            uniqid($this->getService()::SERVICE_NAME)
        );

        // set initial number of consumption attempts
        /** @var Wire\AMQPTable $headers */
        $headers = new Wire\AMQPTable(
            [
                self::PROPERTY_NUMBER_OF_ATTEMPTS => 0,
            ]
        );

        $message->set(self::PROPERTY_APPLICATION_HEADERS, $headers);

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

    /**
     * Increases the message number of consumption attempts
     *
     * @param AMQPMessage $message
     *
     * @return AMQPMessage
     */
    public function addAttempt(AMQPMessage $message)
    {
        $numberOfAttempts = $this->getAttempts($message);

        /** @var Wire\AMQPTable $headers */
        $headers = new Wire\AMQPTable();

        $headers->set(self::PROPERTY_NUMBER_OF_ATTEMPTS, $numberOfAttempts + 1, Wire\AMQPTable::T_INT_SHORT);
        $message->set(self::PROPERTY_APPLICATION_HEADERS, $headers);

        return $message;
    }

    /**
     * Returns the number of message consumption attempts
     *
     * @param AMQPMessage $message
     *
     * @return int
     */
    public function getAttempts(AMQPMessage $message)
    {
        $currentHeaders = $message->get(self::PROPERTY_APPLICATION_HEADERS)->getNativeData();

        if (isset($currentHeaders[self::PROPERTY_NUMBER_OF_ATTEMPTS])) {
            return (int) $currentHeaders[self::PROPERTY_NUMBER_OF_ATTEMPTS];
        } else {
            return 0;
        }
    }
}
