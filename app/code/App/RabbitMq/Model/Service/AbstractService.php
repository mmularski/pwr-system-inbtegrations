<?php

namespace App\RabbitMq\Model\Service;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use Psr\Log\LoggerInterface as AbstractLogger;
use App\RabbitMq\Helper\Server as ServerHelper;
use App\RabbitMq\Model\Service\Consumer\AbstractConsumer;
use App\RabbitMq\Model\Service\Message\AbstractMessage;
use App\RabbitMq\Model\Service\Publisher\AbstractPublisher;
use App\RabbitMq\Model\Service\Queue\AbstractQueue;

/**
 * Class AbstractService
 *
 * @package App\RabbitMq\Model\Service
 */
abstract class AbstractService extends AbstractModel
{
    /**
     * Service name
     */
    const SERVICE_NAME = 'default_service';

    /**
     * Direct exchange type
     */
    const EXCHANGE_TYPE_DIRECT = 'direct';

    /**
     * Fanout exchange type
     */
    const EXCHANGE_TYPE_FANOUT = 'fanout';

    /**
     * Topic exchange type
     */
    const EXCHANGE_TYPE_TOPIC = 'topic';

    /**#@+
     * Available script exit codes
     */
    const EXIT_CODE_SUCCESS = 0;

    const EXIT_CODE_ERROR = 1;

    const EXIT_CODE_TIMEOUT = 2;

    /**#@-*/

    /**#@+
     * Available service types
     */
    const SERVICE_TYPE_REST = 'rest';

    const SERVICE_TYPE_SOAP = 'soap';

    /**#@-*/

    /**
     * Available exchange types
     *
     * @var array $allExchangeTypes
     */
    protected $allExchangeTypes = [
        self::EXCHANGE_TYPE_DIRECT,
        self::EXCHANGE_TYPE_FANOUT,
        self::EXCHANGE_TYPE_TOPIC,
    ];

    /**
     * XML path for Enable/Disable pushing messages
     *
     * @var string
     */
    protected $isEnabledPushXmlPath = '';

    /**
     * XML path for Enable/Disable consuming messages
     *
     * @var string
     */
    protected $isEnabledConsumeXmlPath = '';

    /**
     * @var string $exchangeType
     */
    protected $exchangeType = self::EXCHANGE_TYPE_DIRECT;

    /**
     * @var null|AMQPStreamConnection $connection
     */
    protected $connection;

    /**
     * @var AMQPChannel $chanel
     */
    protected $channel;

    /**
     * @var ServerHelper $serverHelper
     */
    protected $serverHelper;

    /**
     * @var AbstractService $service
     */
    protected $service;

    /**
     * @var AbstractConsumer $consumer
     */
    protected $consumer;

    /**
     * @var AbstractPublisher $publisher
     */
    protected $publisher;

    /**
     * @var AbstractQueue $queue
     */
    protected $queue;

    /**
     * @var AbstractMessage $message
     */
    protected $message;

    /**
     * Current service type
     *
     * @var string $type
     */
    protected $type = self::SERVICE_TYPE_REST;

    /**
     * @var AbstractLogger $logger
     */
    public $logger;

    /**
     * Sets AMQPStreamConnection
     *
     * @return void
     */
    protected function setConnection()
    {
        $this->connection = $this->serverHelper->getConnection();
    }

    /**
     * Sets AMQPChannel channel
     *
     * @return $this
     */
    protected function setChannel()
    {
        $this->channel = $this->connection->channel();

        return $this;
    }

    /**
     * @return AMQPChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * AbstractService constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ServerHelper $serverHelper
     * @param AbstractLogger $logger
     * @param null $queue
     * @param null $publisher
     * @param null $consumer
     * @param null $message
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ServerHelper $serverHelper,
        AbstractLogger $logger,
        $queue = null,
        $publisher = null,
        $consumer = null,
        $message = null
    ) {
        parent::__construct(
            $context,
            $registry
        );

        $this->serverHelper = $serverHelper;
        $this->logger = $logger;

        $this->setConnection();
        $this->setChannel();

        if ($queue instanceof AbstractQueue) {
            $queue->setService($this);
            $this->setQueue($queue);
        }

        if ($publisher instanceof AbstractPublisher) {
            $publisher->setService($this);
            $this->setPublisher($publisher);
        }

        if ($consumer instanceof AbstractConsumer) {
            $consumer->setService($this);
            $this->setConsumer($consumer);
        }

        if ($message instanceof AbstractMessage) {
            $message->setService($this);
            $this->setMessage($message);
        }
    }

    /**
     * @param string $type
     *
     * @return $this
     * @throws \Exception
     */
    public function setExchangeType($type)
    {
        if (!in_array(
            $type,
            $this->allExchangeTypes
        )
        ) {
            throw new \Exception('Incorrect exchange type.');
        }

        $this->exchangeType = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getExchangeType()
    {
        return $this->exchangeType;
    }

    /**
     * @param AbstractService $service
     *
     * @return $this
     */
    public function setService(AbstractService $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return AbstractService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param AbstractConsumer $consumer
     *
     * @return $this
     */
    public function setConsumer(AbstractConsumer $consumer)
    {
        $this->consumer = $consumer;

        return $this;
    }

    /**
     * @return AbstractConsumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * @param AbstractPublisher $publisher
     *
     * @return $this
     */
    public function setPublisher(AbstractPublisher $publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return AbstractPublisher
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param AbstractQueue $queue
     *
     * @return $this
     */
    public function setQueue(AbstractQueue $queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * @return AbstractQueue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param AbstractMessage $message
     *
     * @return $this
     */
    public function setMessage(AbstractMessage $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return AbstractMessage
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function declareQueue()
    {
        try {
            $this->channel->queue_declare(
                $this->queue->getName(),
                false,
                true,
                false,
                false
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Closes channel
     *
     * @return void
     */
    public function closeChannel()
    {
        $this->logger->info('Closing channel...');
        $this->channel->close();
    }

    /**
     * Closes connection
     *
     * @return void
     */
    public function closeConnection()
    {
        $this->logger->info('Cosing connection...');
        $this->connection->close();
    }
}
