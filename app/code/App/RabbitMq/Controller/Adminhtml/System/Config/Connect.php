<?php

namespace App\RabbitMq\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\RabbitMq\Helper\Config as ConfigHelper;
use App\RabbitMq\Helper\Server as ServerHelper;

/**
 * Class Connect
 *
 * @package App\RabbitMq\Controller\Adminhtml\System\Config\Connect
 */
class Connect extends Action
{
    /** @var JsonFactory $resultJsonFactory */
    protected $resultJsonFactory;

    /**
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * @var ServerHelper $serverHelper
     */
    protected $serverHelper;

    /**
     * Connect constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param ConfigHelper $configHelper
     * @param ServerHelper $serverHelper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        ConfigHelper $configHelper,
        ServerHelper $serverHelper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->configHelper = $configHelper;
        $this->serverHelper = $serverHelper;
        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();
        $resultsData = [];

        try {
            /** @var AMQPStreamConnection $connection */
            $connection = $this->connect();

            $resultsData['success'] = $connection->isConnected();
        } catch (\Exception $e) {
            $resultsData['success'] = false;
            $resultsData['message'] = $e->getMessage();
            $resultsData['trace'] = $e->getTraceAsString();
            $resultsData['code'] = $e->getCode();
        }

        return $result->setData($resultsData);
    }

    /**
     * Returns AMQP connection object
     *
     * @return AMQPStreamConnection
     */
    protected function connect()
    {
        return $this->serverHelper->getConnection();
    }
}
