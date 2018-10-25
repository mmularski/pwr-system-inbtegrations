<?php

namespace App\ProductUpdater\Api\Data;

/**
 * Interface UpdateRequestInterface
 */
interface UpdateRequestInterface
{
    /**#@+
     * Defined parameters names
     */
    const KEY_PRODUCT_SKU = 'productSku';

    const KEY_DIFF = 'diff';
    /**#@-*/

    /**
     * @return string
     */
    public function getProductSku();

    /**
     * @param $sku
     *
     * @return $this
     */
    public function setProductSku($sku);

    /**
     * @return string
     */
    public function getDiff();

    /**
     * @param string $diff
     *
     * @return $this
     */
    public function setDiff($diff);
}
