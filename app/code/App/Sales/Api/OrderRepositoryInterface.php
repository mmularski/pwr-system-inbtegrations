<?php

namespace App\Sales\Api;

/**
 * Interface OrderRepositoryInterface
 */
interface OrderRepositoryInterface
{
    /**
     * @api
     *
     * @param int $id
     *
     * @return string[]
     */
    public function get($id);
}
