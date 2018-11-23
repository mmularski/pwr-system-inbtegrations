<?php

namespace App\Inpost\Api;

/**
 * Interface PointServiceInterface
 */
interface PointServiceInterface
{
    /**
     * @return array
     */
    public function getPoints();

    /**
     * @param string $pointName
     *
     * @return bool
     */
    public function savePoint($pointName);
}
