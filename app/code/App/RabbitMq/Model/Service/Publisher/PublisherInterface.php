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
     * Prepares data for request
     *
     * @return mixed
     */
    public function prepareData();

    /**
     * Generates and returns request to store it on queue for further processing
     *
     * @return array|string|mixed
     */
    public function getRequest();

    /**
     * Pushes new job to queue or process it immediately
     *
     * @param mixed $model
     * @param bool $skipQueue
     *
     * @return mixed
     */
    public function push($model, $skipQueue = false);

    /**
     * Sets model for further processing and request / job preparation
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function setModel($model);
}
