<?php

namespace App\ProductUpdater\Model;

use App\ProductUpdater\Api\Data\UpdateRequestInterface;
use App\ProductUpdater\Api\ProductUpdateInterface;
use App\RabbitMq\Helper\Server as ServerHelper;
use App\RabbitMq\Model\Service\AbstractService;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface as AbstractLogger;

/**
 * Class Service.
 */
class Service extends AbstractService implements ProductUpdateInterface
{
    /**
     * Service name
     */
    const SERVICE_NAME = 'product_updater';

    /**
     * Service constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ServerHelper $serverHelper
     * @param AbstractLogger $logger
     * @param Queue $queue
     * @param Publisher $publisher
     * @param Consumer $consumer
     * @param Message $message
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ServerHelper $serverHelper,
        AbstractLogger $logger,
        Queue $queue,
        Publisher $publisher,
        Consumer $consumer,
        Message $message
    ) {
        parent::__construct($context, $registry, $serverHelper, $logger, $queue, $publisher, $consumer, $message);
    }

    /**
     * @api
     *
     * @param UpdateRequestInterface $object
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function execute(UpdateRequestInterface $object)
    {
        return $this->getPublisher()->push($object);
    }
}
