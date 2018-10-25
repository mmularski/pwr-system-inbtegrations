<?php
/**
 * @package   App\RabbitMq
 * @author    Wiktor Kaczorowski <wkaczorowski@App.pl>
 * @copyright 2016-2018 App Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace App\RabbitMq\Model\Service\Consumer;

use App\RabbitMq\Model\Service\AbstractService;
use App\RabbitMq\Model\Service\AbstractElement;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use App\RabbitMq\Model\Service\Message\AbstractMessage;

/**
 * Class AbstractConsumer
 */
abstract class AbstractConsumer extends AbstractElement implements ConsumerInterface
{
    /**
     * Consumer maximum lifetime in seconds
     */
    const CONSUME_LIFETIME_SECONDS = 3;

    /**
     * Success response status
     */
    const STATUS_OK = 200;

    /**
     * Determines, if an acknowledgment should be added to the message, to remove properly consumed message from the
     * queue. Otherwise the message will persists on the queue and will be consumed again.
     *
     * @var bool $acknowledgment
     */
    protected $acknowledgment = true;

    /**
     * Defines maximum number of unconsumed messages dispatched to the consumer at the same time
     *
     * @var int $prefetchCount
     */
    protected $prefetchCount = 1;

    /**
     * This flag minimizes worker log output
     *
     * @var bool
     */
    protected $quietMode = true;

    /**
     * @var Trash
     */
    protected $trash;

    /**
     * @param int $count
     *
     * @return $this
     */
    public function setPrefetchCount($count)
    {
        $this->prefetchCount = $count;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrefetchCount()
    {
        return $this->prefetchCount;
    }

    /**
     * Sets prefetch count for the channel
     *
     * @return void;
     */
    public function basicQos()
    {
        $this->getService()->getChannel()->basic_qos(
            null,
            $this->prefetchCount,
            null
        );
    }

    /**
     * Consumer callback method
     *
     * @param AMQPMessage $message
     * @param bool $skipQueue
     * @param bool $rePublish When this flag is set to true, then message will be published back to queue
     *
     * @return mixed;
     */
    public function callback(AMQPMessage $message, $skipQueue = false, $rePublish = false)
    {
        $msgId = $this->getService()->getMessage()->getAMQPMsgId($message);

        $this->getService()->logger->info(
            sprintf(
                'Message: %s headers: %s.',
                $msgId,
                print_r(
                    $message->get($this->getService()->getMessage()::PROPERTY_APPLICATION_HEADERS)->getNativeData(),
                    true
                )
            )
        );

        // increase consumption attempts number
        $this->getService()->getMessage()->addAttempt($message);

        if ((true === $this->acknowledgment) && !$skipQueue) {
            // @codingStandardsIgnoreStart
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            // @codingStandardsIgnoreEnd

            $this->getService()->logger->info(
                'Acknowledgement added for message: ' . $msgId
            );

            print_r(
                $message->get($this->getService()->getMessage()::PROPERTY_APPLICATION_HEADERS)->getNativeData(),
                true
            );

            if (true === $rePublish) {
                if ($this->getService()->getMessage()->getAttempts($message) <= $this->trash->getMaxAttemptsNo()) {
                    $this->getService()->logger->info(sprintf('Message %s republishing...', $msgId));

                    $this->rePublishMessage($message);
                } else {
                    $this->moveToTrash($message);
                }
            }
        }
    }

    /**
     * Returns current time
     *
     * @return mixed
     */
    protected function getCurrentTime()
    {
        return microtime(true);
    }

    /**
     * Returns time difference
     *
     * @param float $startTime
     *
     * @return mixed
     */
    protected function getTimeDiff($startTime)
    {
        return $this->getCurrentTime() - $startTime;
    }

    /**
     * Publish the message back to queue
     *
     * @param AMQPMessage $message
     */
    public function rePublishMessage(AMQPMessage $message)
    {
        $this->getService()->getPublisher()->publish($this->getService()->getMessage()->setAMQPMessage($message));
    }

    /**
     * Currently trash is a separate log file. Handling trash as a separate queue would be an improvement but it would
     * require additional handling of removed messages.
     *
     * @param AMQPMessage $message
     */
    public function moveToTrash(AMQPMessage $message)
    {
        $msgId = $this->getService()->getMessage()->getAMQPMsgId($message);

        if (!$this->trash->isEnabled()) {
            $errorMsg = sprintf(
                'Message: %s reaches maximum consumption attempts number: %d and was permanently removed.',
                $msgId,
                $this->trash->getMaxAttemptsNo()
            );

            $this->getService()->logger->critical($errorMsg);

            return;
        }

        $errorMsg = sprintf(
            'Message: %s reaches maximum consumption attempts number: %d and was moved to trash.',
            $msgId,
            $this->trash->getMaxAttemptsNo()
        );

        $this->getService()->logger->critical($errorMsg);

        $this->trash->info(sprintf('Message %s moved to trash.', $msgId));

        $messageLogInfo = [
            'queue' => $this->getService()->getQueue()->getName(),
            'message_id' => $msgId,
            'headers' => print_r(
                $message->get(AbstractMessage::PROPERTY_APPLICATION_HEADERS)->getNativeData(),
                true
            ),
            'endpoint' => $this->getService()->getEndpoint(),
            'body_size' => $message->getBodySize(),
            'body_length' => strlen($message->getBody()),
            'body' => $message->getBody(),
        ];

        $this->trash->info(print_r($messageLogInfo, true));
    }

    /**
     * Consume messages from channel
     *
     * @return int
     */
    public function consume()
    {
        $this->basicQos();
        $this->getService()->declareQueue();

        $this->getService()->getChannel()->basic_consume(
            $this->getService()->getQueue()->getName(),
            '',
            false,
            false,
            false,
            false,
            [
                $this,
                'callback',
            ]
        );

        $startTime = $this->getCurrentTime();
        while (count($this->getService()->getChannel()->callbacks)) {
            try {
                $this->getService()->getChannel()->wait(
                    null,
                    true,
                    1
                );
            } catch (AMQPTimeoutException $e) {
                $processingTime = $this->getTimeDiff($startTime);
                if ($processingTime >= self::CONSUME_LIFETIME_SECONDS) {
                    if (!$this->quietMode) {
                        $this->getService()->logger->info(
                            'Consumer exceeded max execution time (' . static::CONSUME_LIFETIME_SECONDS . 'sec).'
                        );
                    }

                    return AbstractService::EXIT_CODE_TIMEOUT;
                }
            } catch (\Exception $e) {
                $this->getService()->logger->exception($e);

                return AbstractService::EXIT_CODE_ERROR;
            }
        }

        $this->getService()->closeChannel();
        $this->getService()->closeConnection();

        return AbstractService::EXIT_CODE_SUCCESS;
    }
}
