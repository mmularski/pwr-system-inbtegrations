<?php

namespace App\Inpost\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use App\Inpost\Api\Data\PointInterface;

/**
 * Interface PointRepositoryInterface
 */
interface PointRepositoryInterface
{
    /**
     * Retrieve blocks matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Retrive all ids matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return array
     */
    public function getAllIds(SearchCriteriaInterface $searchCriteria);

    /**
     * Method save model.
     *
     * @param AbstractModel $model
     *
     * @throws CouldNotSaveException
     *
     * @return PointInterface
     */
    public function save($model);

    /**
     * Method returns model by id.
     *
     * @param int $entityId
     *
     * @throws NoSuchEntityException
     *
     * @return PointInterface
     */
    public function getById($entityId);
}
