<?php

namespace App\Inpost\Api;

/**
 * Interface PointServiceInterface
 */
interface PointServiceInterface
{
    /**
     * @param string $query
     *
     * @return array
     */
    public function getPoints($query = 'all');

    /**
     * @param string $cartId
     * @param string $pointName
     *
     * @return bool
     */
    public function savePoint($cartId, $pointName);
}
