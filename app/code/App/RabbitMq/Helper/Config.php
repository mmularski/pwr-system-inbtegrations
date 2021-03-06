<?php

namespace App\RabbitMq\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;

/**
 * Class Config
 */
class Config extends AbstractHelper
{
    /**
     * Configuration path to connection host
     */
    const XML_PATH_RABBITMQ_CONNECTION_HOST = 'rabbitmq/server_connection/host';

    /**
     * Configuration path to connection port
     */
    const XML_PATH_RABBITMQ_CONNECTION_PORT = 'rabbitmq/server_connection/port';

    /**
     * Configuration path to connection user
     */
    const XML_PATH_RABBITMQ_CONNECTION_USER = 'rabbitmq/server_connection/user';

    /**
     * Configuration path to connection password
     */
    const XML_PATH_RABBITMQ_CONNECTION_PASSWORD = 'rabbitmq/server_connection/password';

    /**
     * Configuration path to connection virtual host
     */
    const XML_PATH_RABBITMQ_CONNECTION_VHOST = 'rabbitmq/server_connection/vhost';

    /**
     * @var EncryptorInterface $encryptor
     */
    protected $encryptor;

    /**
     * Config constructor.
     *
     * @param Context $context
     * @param EncryptorInterface $encryptor
     */
    public function __construct(Context $context, EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;
        parent::__construct($context);
    }

    /**
     * Returns connection host name or ip address
     *
     * @return string
     */
    public function getHost()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_RABBITMQ_CONNECTION_HOST);
    }

    /**
     * Returns connection port
     *
     * @return string
     */
    public function getPort()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_RABBITMQ_CONNECTION_PORT);
    }

    /**
     * Returns connection user
     *
     * @return string
     */
    public function getUser()
    {
        $userEncrypted = $this->scopeConfig->getValue(self::XML_PATH_RABBITMQ_CONNECTION_USER);

        return $this->encryptor->decrypt($userEncrypted);
    }

    /**
     * Returns connection password
     *
     * @return string
     */
    public function getPassword()
    {
        $passEncrypted = $this->scopeConfig->getValue(self::XML_PATH_RABBITMQ_CONNECTION_PASSWORD);

        return $this->encryptor->decrypt($passEncrypted);
    }

    /**
     * Returns connection virtual host
     *
     * @return string
     */
    public function getVhost()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_RABBITMQ_CONNECTION_VHOST);
    }
}
