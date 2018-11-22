<?php

namespace App\Inpost\Model\ResourceModel;

use Magento\ProductAlert\Model\ResourceModel\AbstractResource;

/**
 * Class Point
 */
class Point extends AbstractResource
{
    /**
     * Main table
     */
    const MAIN_TABLE = 'inpost_points';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, 'entity_id');
    }
}
