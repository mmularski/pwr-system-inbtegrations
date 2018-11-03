<?php

namespace App\ProductUpdater\Model;

use App\ProductUpdater\Api\Data\UpdateRequestInterface;
use App\ProductUpdater\Api\ProductUpdateInterface;
use App\RabbitMq\Helper\Server as ServerHelper;
use App\RabbitMq\Model\Service\AbstractService;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use App\RabbitMq\Logger\Logger;

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
     * @param Logger $logger
     * @param Queue $queue
     * @param Publisher $publisher
     * @param Consumer $consumer
     * @param Message $message
     *
     * @throws \Exception
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ServerHelper $serverHelper,
        Logger $logger,
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
