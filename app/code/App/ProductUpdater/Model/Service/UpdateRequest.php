<?php

namespace App\ProductUpdater\Model\Service;

use App\ProductUpdater\Api\Data\UpdateRequestInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class UpdateRequest
 */
class UpdateRequest extends AbstractModel implements UpdateRequestInterface
{

    /**
     * @return string
     */
    public function getProductSku()
    {
        return $this->getData(self::KEY_PRODUCT_SKU);
    }

    /**
     * @param $sku
     *
     * @return $this
     */
    public function setProductSku($sku)
    {
        return $this->setData(self::KEY_PRODUCT_SKU, $sku);
    }

    /**
     * @return string
     */
    public function getDiff()
    {
        return $this->getData(self::KEY_DIFF);
    }

    /**
     * @param string $diff
     *
     * @return $this
     */
    public function setDiff($diff)
    {
        return $this->setData(self::KEY_DIFF, $diff);
    }
}
