<?php

namespace App\Inpost\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PointSearchResultInterface
 *
 * @api
 */
interface PointSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get points.
     *
     * @return PointInterface[]
     */
    public function getItems();

    /**
     * Set points.
     *
     * @param PointInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items = null);
}
