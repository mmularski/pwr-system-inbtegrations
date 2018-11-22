<?php

namespace App\Inpost\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Config
 */
class Config extends AbstractHelper
{
    /**
     * XML path for config is module enabled
     */
    const XML_IS_ENABLED = 'inpost/general/enabled';

    /**
     * XML path for config is sandbox enabled
     */
    const XML_IS_SANDBOX = 'inpost/general/sandbox';

    /**
     * XML path for config API token
     */
    const XML_API_TOKEN = 'inpost/general/token';

    /**
     * XML path for config live API url
     */
    const XML_LIVE_API_URL = 'inpost/general/api_url';

    /**
     * XML path for config test API url
     */
    const XML_TEST_API_URL = 'inpost/general/sandbox_api_url';

    /**
     * @param string $path
     *
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path);
    }
}
