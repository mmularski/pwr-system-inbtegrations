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
     * Generates and returns request to store it on queue for further processing
     *
     * @return array|string|mixed
     */
    public function getRequest();

    /**
     * Pushes new job to queue
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function push($model);

    /**
     * Sets model for further processing and request / job preparation
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function setModel($model);
}
