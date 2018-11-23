<?php

namespace App\Inpost\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Exception\LocalizedException;
use App\Inpost\Api\Data\PointInterface;
use App\Inpost\Api\PointRepositoryInterface;
use App\Inpost\Api\PointServiceInterface;
use App\Inpost\Logger\Logger;

/**
 * Class PointService
 */
class PointService implements PointServiceInterface
{
    /**
     * @var PointRepositoryInterface
     */
    protected $pointRepository;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * PointService constructor.
     *
     * @param PointRepositoryInterface $pointRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param Logger $logger
     * @param Session $customerSession
     */
    public function __construct(
        PointRepositoryInterface $pointRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        Logger $logger,
        Session $customerSession
    ) {
        $this->pointRepository = $pointRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
    }

    /**
     * @return array
     */
    public function getPoints()
    {
        $result = [];

        /** @var SearchCriteriaBuilder $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria->addFilter(PointInterface::TO_DELETE_FLAG, 0, 'eq');

        try {
            $result = $this->pointRepository->getList($searchCriteria->create())->getItems();
        } catch (LocalizedException $e) {
            $this->logger->error(
                sprintf('getPoints search error. Exception message: %s. Query was: %s', $e->getMessage(), $query)
            );
        }

        return $result;
    }

    /**
     * @param string $pointName
     *
     * @return bool
     */
    public function savePoint($pointName)
    {
        try {
            $customer = $this->customerSession->getCustomer();
            $customer->getResource()->getConnection()->update(
                'customer_entity',
                ['inpost_point' => $pointName],
                ['entity_id=?' => $customer->getEntityId()]
            );

            return true;
        } catch (\Exception $ex) {
            $this->logger->error(sprintf('Point save error. Exception Message: %s', $ex->getMessage()));
        }

        return false;
    }
}
