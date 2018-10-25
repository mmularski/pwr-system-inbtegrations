<?php

namespace App\RabbitMq\Model\Service\Publisher;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use App\RabbitMq\Helper\Logger as LoggerHelper;
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
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var LoggerHelper
     */
    protected $loggerHelper;

    /**
     * AbstractPublisher constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerHelper $loggerHelper
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $scopeConfig,
        LoggerHelper $loggerHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->loggerHelper = $loggerHelper;

        parent::__construct($context, $registry);
    }

    /**
     * Cleans up input string from unnecessary characters
     *
     * @param string $string
     *
     * @return mixed
     */
    protected function cleanUpString(&$string)
    {
        if (!is_string($string)) {
            return $string;
        }

        // decode html entities in case of existing already encoded
        $string = html_entity_decode($string);
        // encode html entities with double and single quotes
        $string = htmlentities($string, ENT_QUOTES);
        // remove encoded html entities and multiple spaces (two or more)
        $string = preg_replace(['/&#?[a-z0-9]{2,8};/i', '/(\s+){2,}/'], "", $string);

        return $string;
    }

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
     * @param bool $jsonEncoded
     * @param bool $pretty
     *
     * @return array
     */
    public function getRequestData($jsonEncoded = false, $pretty = false)
    {
        if (true === $jsonEncoded) {
            if (true === $pretty) {
                return json_encode(
                    $this->requestData,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                );
            }

            return json_encode($this->requestData, JSON_UNESCAPED_UNICODE);
        }

        return $this->requestData;
    }

    /**
     * @param AbstractMessage $message
     *
     * @return void
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
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function push($model, $skipQueue = false)
    {
        // $model object should be set in the child Publisher class

        try {
            $request = $this->getRequest();
            $this->getService()->getMessage()->createMessage($request);

            if (!$skipQueue) {
                $this->publish($this->getService()->getMessage());

                $logData = $this->getRequestData(true, true);

                $logInfo = [
                    'Message ' . $this->getService()->getMessage()->getAMQPMsgId() . ' sent to queue:',
                    'body: ' . $logData,
                ];

                $this->getService()->logger->info(implode(PHP_EOL, $logInfo));

                // replace message body with body length in response
                return [
                    'Message ' . $this->getService()->getMessage()->getAMQPMsgId() . ' sent to queue:',
                    sprintf('body: length-%s.', strlen($this->getRequestData(true))),
                ];
            } else {
                $logInfo = [
                    'Message processed without queue: ',
                    'body: ' . $this->getRequestData(
                        true,
                        true
                    ),
                ];
                $this->getService()->logger->info(
                    implode(
                        PHP_EOL,
                        $logInfo
                    )
                );

                return $this->getService()->getConsumer()->callback(
                    $this->getService()->getMessage()->getAMQPMessage(),
                    $skipQueue
                );
            }
        } catch (\Exception $e) {
            $this->getService()->logger->exception($e);

            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
