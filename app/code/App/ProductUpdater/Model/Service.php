<?php
/**
 * @package  App\ProductUpdater
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace App\ProductUpdater\Model;

use App\RabbitMq\Model\Service\AbstractService;

/**
 * Class Service.
 */
class Service extends AbstractService
{
    /**
     * Service name
     */
    const SERVICE_NAME = 'product_updater';
}
