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
     * @var bool $passive
     */
    protected $passive = false;

    /**
     * Set to true to prevent queue deletion on RabbitMq stop or error
     *
     * @var bool $durable
     */
    protected $durable = true;

    /**
     * Set to true to delete queue after connection close
     *
     * @var bool $exclusive
     */
    protected $exclusive = false;

    /**
     * @var bool $autoDelete
     */
    protected $autoDelete = false;

    /**
     * @var bool $noWait
     */
    protected $noWait = false;

    /**
     * @var null|array $arguments
     */
    protected $arguments;

    /**
     * @var int $ticket
     */
    protected $ticket;

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

    /**
     * @param bool $passive
     *
     * @return $this
     */
    public function setPassive($passive)
    {
        $this->passive = $passive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPassive()
    {
        return $this->passive;
    }

    /**
     * @param bool $durable
     *
     * @return $this
     */
    public function setDurable($durable)
    {
        $this->durable = $durable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDurable()
    {
        return $this->durable;
    }

    /**
     * @param bool $exclusive
     *
     * @return $this
     */
    public function setExclusive($exclusive)
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExclusive()
    {
        return $this->exclusive;
    }

    /**
     * @param bool $autoDelete
     *
     * @return $this
     */
    public function setAutoDelete($autoDelete)
    {
        $this->autoDelete = $autoDelete;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isAutoDelete()
    {
        return $this->autoDelete;
    }
}
