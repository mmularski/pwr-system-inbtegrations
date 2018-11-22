<?php

namespace App\Inpost\Model\ResourceModel\Point;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use App\Inpost\Model\Point;
use App\Inpost\Model\ResourceModel\Point as PointResource;

/**
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    public function _construct()
    {
        parent::_construct();

        $this->_init(Point::class, PointResource::class);
    }
}
