<?php

namespace App\Product\Model\Service;

use App\Product\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as MagentoProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class ProductRepository
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var MagentoProductRepository
     */
    private $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * ProductRepository constructor.
     *
     * @param MagentoProductRepository $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        MagentoProductRepository $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function get()
    {
        $searchCriteria = $this->searchCriteriaBuilder->setPageSize(100)->create();
        $products = $this->productRepository->getList($searchCriteria)->getItems();

        return $products;
    }
}
