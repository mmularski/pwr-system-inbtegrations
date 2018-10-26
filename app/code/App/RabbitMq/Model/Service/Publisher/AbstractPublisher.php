<?php

namespace App\RabbitMq\Model\Service\Publisher;

use App\RabbitMq\Model\Service\AbstractElement;
use App\RabbitMq\Model\Service\Message\AbstractMessage;

/**
 * Class AbstractPublisher
 *
 * @package App\RabbitMq\Model\Publisher
 */
abstract class AbstractPublisher extends AbstractElement implements PublisherInterface
{
    /**
     * @var array $requestData
     */
    protected $requestData;

    /**
     * Sets data for request
     *
     * @param array $data
     *
     * @return $this
     */
    public function setRequestData($data)
    {
        $this->requestData = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestData()
    {
        return json_encode($this->requestData);
    }

    /**
     * @param AbstractMessage $message
     *
     * @return void
     * @throws \Exception
     */
    public function publish(AbstractMessage $message)
    {
        $this->getService()->declareQueue();

        $this->getService()->getChannel()->basic_publish(
            $message->getAMQPMessage(),
            '',
            $this->getService()->getQueue()->getName()
        );
    }

    /**
     * Pushes new job to queue or process it immediately
     *
     * @param mixed $model
     * @param bool $skipQueue
     *
     * @return array|mixed
     * @throws \Exception
     */
    public function push($model)
    {
        // $model object should be set in the child Publisher class

        try {
            $request = $this->getRequest();
            $this->getService()->getMessage()->createMessage($request);

            $this->publish($this->getService()->getMessage());

            $logInfo = [
                'Message ' . $this->getService()->getMessage()->getAMQPMsgId() . ' sent to queue:',
                'body: ' . $this->getRequestData(),
            ];

            $this->getService()->logger->info(implode(PHP_EOL, $logInfo));

            // replace message body with body length in response
            return [
                'Message ' . $this->getService()->getMessage()->getAMQPMsgId() . ' sent to queue:',
                sprintf('body: length-%s.', strlen($this->getRequestData())),
            ];

        } catch (\Exception $e) {
            $this->getService()->logger->error($e);

            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
