<?php

namespace App\RabbitMq\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Logger
 */
class Logger extends AbstractHelper
{
    /**
     * Returns the type of the current environment
     *
     * @return string
     */
    public function getEnvType()
    {
        return 'dev';
    }
}
