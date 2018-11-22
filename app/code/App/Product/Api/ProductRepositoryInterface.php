<?php

namespace App\Product\Api;

/**
 * Interface ProductRepositoryInterface
 */
interface ProductRepositoryInterface
{
    /**
     * @api
     *
     * @return string[]
     */
    public function get();
}
