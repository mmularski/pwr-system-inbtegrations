<?php
/**
 * @package  App\ProductUpdater
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace App\ProductUpdater\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use App\RabbitMq\Model\Service\Queue\AbstractQueue;

/**
 * Class Queue.
 */
class Queue extends AbstractQueue
{
    /**
     * Queue name
     */
    const PRODUCT_UPDATER_QUQUE_NAME = 'product_updater';

    /**
     * AbstractPublisher constructor.
     *
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(Context $context, Registry $registry)
    {
        parent::__construct($context, $registry);

        $this->setName(self::PRODUCT_UPDATER_QUQUE_NAME);
    }
}
