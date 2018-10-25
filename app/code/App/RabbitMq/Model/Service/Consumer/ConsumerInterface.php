<?php
/**
 * @package   App\RabbitMq
 * @author    Wiktor Kaczorowski <wkaczorowski@App.pl>
 * @copyright 2016-2018 App Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace App\RabbitMq\Model\Service\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Interface PublisherInterface
 *
 * @package App\RabbitMq\Model\Service\Publisher
 */
interface ConsumerInterface
{
    /**
     * Callback method for message consumer
     *
     * @param AMQPMessage $message
     * @param bool $skipQueue
     *
     * @return mixed
     */
    public function callback(AMQPMessage $message, $skipQueue = false);
}
