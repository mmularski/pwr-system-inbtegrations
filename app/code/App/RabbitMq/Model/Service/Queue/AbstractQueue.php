<?php

namespace App\RabbitMq\Model\Service\Queue;

use App\RabbitMq\Model\Service\AbstractElement;

/**
 * Class AbstractQueue
 *
 * @package App\RabbitMq\Model\Queue
 */
abstract class AbstractQueue extends AbstractElement
{
    /**
     * @var null|string $name
     */
    protected $name;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }
}
