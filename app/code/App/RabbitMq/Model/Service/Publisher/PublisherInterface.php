<?php

namespace App\RabbitMq\Model\Service\Publisher;

/**
 * Interface PublisherInterface
 *
 * @package App\RabbitMq\Model\Service\Publisher
 */
interface PublisherInterface
{
    /**
     * Pushes new job to queue
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function push($model);
}
