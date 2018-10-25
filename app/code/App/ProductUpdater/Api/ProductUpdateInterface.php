<?php

namespace App\ProductUpdater\Api;

use App\ProductUpdater\Api\Data\UpdateRequestInterface;

/**
 * Interface ProductUpdateInterface
 */
interface ProductUpdateInterface
{
    /**
     * @api
     *
     * @param UpdateRequestInterface $object
     *
     * @return mixed
     */
    public function execute(UpdateRequestInterface $object);
}
