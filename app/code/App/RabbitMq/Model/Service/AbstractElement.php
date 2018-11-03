<?php

namespace App\RabbitMq\Model\Service;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * Class AbstractElement
 *
 * @package App\RabbitMq\Model\Service
 */
abstract class AbstractElement extends AbstractModel
{
    /**
     * @var AbstractService $service
     */
    protected $service;

    /**
     * AbstractElement constructor.
     *
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry
    ) {
        parent::__construct(
            $context,
            $registry
        );
    }

    /**
     * @param AbstractService $service
     *
     * @return $this
     */
    public function setService(AbstractService $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return AbstractService
     */
    public function getService()
    {
        return $this->service;
    }
}
