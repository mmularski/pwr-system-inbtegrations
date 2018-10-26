<?php

namespace App\ProductUpdater\Model;

use App\ProductUpdater\Api\Data\UpdateRequestInterface;
use App\RabbitMq\Model\Service\Publisher\AbstractPublisher;
use App\RabbitMq\Model\Service\Publisher\PublisherInterface;

/**
 * Class Publisher.
 */
class Publisher extends AbstractPublisher implements PublisherInterface
{
    /**
     * @var UpdateRequestInterface
     */
    protected $request;

    /**
     * Pushes new job to queue.
     *
     * @param mixed $model
     * @param bool $skipQueue
     *
     * @return mixed
     * @throws \Exception
     */
    public function push($model)
    {
        $this->setModel($model);
        $this->setRequestData($this->request->getData());

        $this->getService()->logger->info('Product Update Push method invoked for  id: ');

        return parent::push($model);
    }

    /**
     * Generates and returns request to store it on queue for further processing
     *
     * @return string
     */
    public function getRequest()
    {
        return json_encode($this->request);
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
        $this->request = $model;

        return $this;
    }
}
