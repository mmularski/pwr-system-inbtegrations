<?php

namespace App\Inpost\Model\Http;

use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\Webapi\Exception;
use App\Inpost\Helper\Config;

/**
 * Class Client
 */
class Client
{
    /**
     * Status code OK
     */
    const STATUS_OK = 200;

    /**
     * @var ClientFactory
     */
    protected $curlClientFactory;

    /**
     * @var Config
     */
    protected $inpostConfig;

    /**
     * Client constructor.
     *
     * @param ClientFactory $curlClientFactory
     * @param Config $inpostConfig
     */
    public function __construct(ClientFactory $curlClientFactory, Config $inpostConfig)
    {
        $this->curlClientFactory = $curlClientFactory;
        $this->inpostConfig = $inpostConfig;
    }

    /**
     * @return string
     */
    public function getApiBaseUrl()
    {
        $url = $this->inpostConfig->getConfig(Config::XML_LIVE_API_URL);

        if ($this->inpostConfig->getConfig(Config::XML_IS_SANDBOX)) {
            $url = $this->inpostConfig->getConfig(Config::XML_TEST_API_URL);
        }

        return $url;
    }

    /**
     * Checks connection between Magento and InPost
     *
     * @return bool
     */
    public function checkConnection()
    {
        $url = $this->getApiBaseUrl();
        $client = $this->curlClientFactory->create();

        $client->get($url);

        if (self::STATUS_OK !== $client->getStatus()) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     *
     * @throws ExceptionimportPointsMeta
     * @throws \Exception
     */
    public function importPointsMeta()
    {
        return $this->importPointsData()['meta'];
    }

    /**
     * @param int $page
     *
     * @return array
     *
     * @throws Exception
     * @throws \Exception
     */
    public function importPointsData($page = 1)
    {
        $url = $this->getApiBaseUrl() . '/v1/points?page=' . $page;
        $client = $this->curlClientFactory->create();

        $client->addHeader('X-User-Agent', 'PWR - Magento2');
        $client->addHeader(
            'Authorization',
            sprintf('%s %s', 'Bearer', $this->inpostConfig->getConfig(Config::XML_API_TOKEN))
        );

        $client->get($url);

        if (self::STATUS_OK !== $client->getStatus()) {
            throw new Exception(__('Could not connect to InPost API'), 0, $client->getStatus(), [$client->getBody()]);
        }

        try {
            $result = \Zend_Json::decode($client->getBody());
        } catch (\Zend_Json_Exception $e) {
            throw new \Exception(__('Could not parse response body from API'));
        }

        return $result;
    }
}
