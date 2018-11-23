<?php

namespace App\Inpost\Model\Observer;

use Magento\Customer\Model\Customer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use App\Inpost\Model\Carrier\Inpost;
use Magento\Sales\Model\Order;

/**
 * Class InpostHandler
 */
class InpostHandler implements ObserverInterface
{
    /**
     * @var Inpost
     */
    protected $inpostMethod;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * InpostHandler constructor.
     *
     * @param Inpost $inpostMethod
     * @param Customer $customer
     */
    public function __construct(Inpost $inpostMethod, Customer $customer)
    {
        $this->inpostMethod = $inpostMethod;
        $this->customer = $customer;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        $customer = $this->customer->load($order->getCustomerId());

        if ($order->getShippingMethod() === 'flatrate_flatrate') {
            $order->setInpostPoint($customer->getInpostPoint());
        }
    }
}
