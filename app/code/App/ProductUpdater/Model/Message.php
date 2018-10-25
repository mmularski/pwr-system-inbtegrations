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
use App\RabbitMq\Model\Service\Message\AbstractMessage;

/**
 * Class Message.
 */
class Message extends AbstractMessage
{
    /**
     * Message constructor.
     *
     * @param Context $context
     * @param Registry $registry
     *
     * @throws \Exception
     */
    public function __construct(Context $context, Registry $registry)
    {
        parent::__construct($context, $registry);

        $this->setContentType(self::CONTENT_TYPE_JSON);
    }
}
