<?php

namespace App\Sales\Model\Service;

use App\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderRepository as MagentoOrderRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class OrderRepository
 */
class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var MagentoOrderRepository
     */
    private $orderRepository;

    /**
     * OrderRepository constructor.
     *
     * @param OrderRepository $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        MagentoOrderRepository $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return string[]
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id)
    {
        $order = $this->orderRepository->get($id);

        return json_encode($order->getData());
    }
}
