<?php
/**
 * @package  App\ProductUpdater
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace App\ProductUpdater\Model;

use App\RabbitMq\Model\Service\Publisher\AbstractPublisher;
use App\RabbitMq\Model\Service\Publisher\PublisherInterface;

/**
 * Class Publisher.
 */
class Publisher extends AbstractPublisher implements PublisherInterface
{
    protected $model;

    /**
     * Prepares data for request
     *
     * @return mixed
     */
    public function prepareData()
    {
        $this->requestData = ['aadsadasdasd'];

        return $this;
    }

    /**
     * Pushes new job to queue.
     *
     * @param mixed $model
     * @param bool $skipQueue
     *
     * @return mixed
     */
    public function push($model, $skipQueue = false)
    {
        $service = $this->getService();

        $service->logger->info('Product Update Push method invoked for  id: ');

        return parent::push($model, $skipQueue);
    }

    /**
     * Generates and returns request to store it on queue for further processing
     *
     * @return string
     */
    public function getRequest()
    {
        $this->prepareData();

        return $this->getRequestData(true);
    }

    /**
     * Sets model for further processing and request / job preparation
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
