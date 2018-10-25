<?php
/**
 * @package   App\RabbitMq
 * @author    Wiktor Kaczorowski <wkaczorowski@App.pl>
 * @copyright 2016-2018 App Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace App\RabbitMq\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\RabbitMq\Helper\Config as ConfigHelper;

/**
 * Class Server
 */
class Server extends AbstractHelper
{
    /**
     * @var AMQPStreamConnection $amqpConnection
     */
    protected $amqpConnection;

    /**
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * Server constructor.
     *
     * @param Context $context
     * @param ConfigHelper $configHelper
     */
    public function __construct(Context $context, ConfigHelper $configHelper)
    {
        $this->configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Establishes connection with RabbitMq Server
     *
     * @param bool $isAjax
     *
     * @return bool|\Exception
     * @throws \Exception
     *
     * @return void
     */
    protected function connect($isAjax = false)
    {
        if (!$this->isModuleEnabled()) {
            return false;
        }

        try {
            $connection = new AMQPStreamConnection(
                $this->configHelper->getHost(),
                $this->configHelper->getPort(),
                $this->configHelper->getUser(),
                $this->configHelper->getPassword(),
                $this->configHelper->getVhost()
            );

            $this->amqpConnection = $connection;
        } catch (\Exception $e) {
            if (true === $isAjax) {
                return $e;
            } else {
                throw $e;
            }
        }
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->configHelper->isEnabled();
    }

    /**
     * Returns AMQP connection object
     *
     * @return AMQPStreamConnection
     */
    public function getConnection()
    {
        if (!$this->amqpConnection instanceof AMQPStreamConnection) {
            $this->connect();
        }

        return $this->amqpConnection;
    }
}
