<?php
/**
 * @package   App\RabbitMq
 * @author    Wiktor Kaczorowski <wkaczorowski@App.pl>
 * @copyright 2016-2018 App Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace App\RabbitMq\Model\Service\Publisher;

use PhpAmqpLib\Message\AMQPMessage;
use Magento\Framework\Model\AbstractModel;

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
