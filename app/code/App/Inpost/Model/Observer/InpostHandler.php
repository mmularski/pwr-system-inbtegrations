<?php

namespace App\Inpost\Model\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use App\Inpost\Model\Carrier\Inpost;
use Magento\Quote\Model\Quote;
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
     * InpostHandler constructor.
     *
     * @param Inpost $inpostMethod
     */
    public function __construct(Inpost $inpostMethod)
    {
        $this->inpostMethod = $inpostMethod;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getData('quote');

        /** @var Order $order */
        $order = $observer->getData('order');

        if (
            null !== $quote->getInpostName()
            && $order->getShippingMethod() === $this->inpostMethod->getMethod()
        ) {
            $order->setInpostName($quote->getInpostName());
        } else {
            $order->setInpostName('');
            $quote->setInpostName('');
        }
    }
}
