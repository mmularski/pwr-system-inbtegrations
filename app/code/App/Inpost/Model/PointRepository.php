<?php

namespace App\Inpost\Model;

use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use App\Inpost\Api\Data\PointInterface;
use App\Inpost\Api\Data\PointSearchResultInterface;
use App\Inpost\Api\Data\PointSearchResultInterfaceFactory;
use App\Inpost\Api\PointRepositoryInterface;
use App\Inpost\Model\PointFactory;
use App\Inpost\Model\ResourceModel\Point as PointResource;
use App\Inpost\Model\ResourceModel\Point\CollectionFactory;

/**
 * Class PointRepository
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PointRepository implements PointRepositoryInterface
{
    /**
     * @var PointResource
     */
    protected $modelResource;

    /**
     * @var PointFactory
     */
    protected $modelFactory;

    /**
     * @var PointSearchResultInterfaceFactory
     */
    protected $searchResultFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * PointRepository constructor.
     *
     * @param PointResource $modelResource
     * @param PointFactory $modelFactory
     * @param PointSearchResultInterfaceFactory $searchResultFactory
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        PointResource $modelResource,
        PointFactory $modelFactory,
        PointSearchResultInterfaceFactory $searchResultFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->modelResource = $modelResource;
        $this->modelFactory = $modelFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var PointResource\Collection $collection */
        $collection = $this->collectionFactory->create();

        /** @var FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders === null) {
            $sortOrders = [];
        }

        /** @var SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $points = [];
        /** @var Point $pointModel */
        foreach ($collection->getItems() as $pointModel) {
            $points[] = $pointModel->getData();
        }

        /** @var PointSearchResultInterface $searchResults */
        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($points);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIds(SearchCriteriaInterface $searchCriteria)
    {
        try {
            $points = $this->getList($searchCriteria)->getItems();
        } catch (LocalizedException $e) {
            $points = [];
        }

        $result = [];

        /**
         * @var PointInterface $point
         */
        foreach ($points as $point) {
            $result[] = $point->getEntityId();
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function save($model)
    {
        try {
            $this->modelResource->save($model);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        /** @var Point $model */
        $model = $this->modelFactory->create();

        $this->modelResource->load($model, $entityId);

        if (!$model->getId()) {
            throw new NoSuchEntityException(__('InPost point with entity id "%1" does not exist.', $entityId));
        }

        return $model;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param PointResource\Collection $collection
     *
     * @return void
     */
    protected function addFilterGroupToCollection(FilterGroup $filterGroup, PointResource\Collection $collection)
    {
        $fields = [];
        $conditions = [];

        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }
}
