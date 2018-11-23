<?php

namespace App\Inpost\Block\Customer;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;

/**
 * Class Point
 */
class Point extends Template
{
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * Point constructor.
     *
     * @param Session $customerSession
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Session $customerSession, Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
    }

    public function getCustomerInpostPoint()
    {
        return $this->customerSession->getCustomer()->getInpostPoint() ?? 'Not set';
    }

    public function getInpostUrl()
    {
        return 'https://magento2.local/rest/V1/inpost/points';
    }

    public function getSaveInpostUrl()
    {
        return 'https://magento2.local/rest/V1/customer/inpost/save';
    }
}
