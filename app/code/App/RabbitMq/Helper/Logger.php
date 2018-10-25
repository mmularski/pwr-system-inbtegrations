<?php
/**
 * @package   App\RabbitMq
 * @author    Wiktor Kaczorowski <wkaczorowski@App.pl>
 * @copyright 2016-2018 App Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

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
