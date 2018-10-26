<?php

namespace App\ProductUpdater\Model;

use App\RabbitMq\Model\Service\Publisher\AbstractPublisher;
use App\RabbitMq\Model\Service\Publisher\PublisherInterface;

/**
 * Class Publisher.
 */
class Publisher extends AbstractPublisher implements PublisherInterface
{
    /**
     * Pushes new job to queue.
     *
     * @param mixed $model
     *
     * @return mixed
     * @throws \Exception
     */
    public function push($model)
    {
        $this->getService()->logger->info('Product Update Push method invoked for  id: ');

        return parent::push($model);
    }
}
