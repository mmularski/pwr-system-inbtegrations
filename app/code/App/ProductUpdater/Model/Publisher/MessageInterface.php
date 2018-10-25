<?php
/**
 * @package  App\ProductUpdater
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace App\ProductUpdater\Model\Publisher;

use Magento\Sales\Model\Order;

/**
 * Interface MessageInterface
 */
interface MessageInterface
{
    /**#@+
     * Defined keys for Order cancel parameters names
     */
    const KEY_UPDATE_PRODUCT = 'UpdateProduct';

    const KEY_PRODUCT_SKU = 'ProductSku';

    const KEY_DIFF = 'Diff';

    /**#@-*/

    /**
     * @param Order $order
     *
     * @return mixed
     */
    public function setOrder(Order $order);

    /**
     * @return integer
     */
    public function getOrderId();

    /**
     * @return string
     */
    public function getTimestamp();
}
