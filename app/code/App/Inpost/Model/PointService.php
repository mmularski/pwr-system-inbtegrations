<?php

namespace App\Inpost\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteIdMaskFactory;
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
     * @var QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * PointService constructor.
     *
     * @param PointRepositoryInterface $pointRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param Logger $logger
     * @param QuoteFactory $quoteFactory
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        PointRepositoryInterface $pointRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        Logger $logger,
        QuoteFactory $quoteFactory,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->pointRepository = $pointRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->logger = $logger;
        $this->quoteFactory = $quoteFactory;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public function getPoints($query = 'all')
    {
        $result = [];

        /** @var SearchCriteriaBuilder $searchCriteria */
        $searchCriteria = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria->addFilter(PointInterface::TO_DELETE_FLAG, 0, 'eq');

        if (preg_match('~[0-9]~', $query)) {
            $searchCriteria->addFilter(PointInterface::POST_CODE, $query, 'like');
        } elseif ('all' != $query) {
            $searchCriteria->addFilter(
                PointInterface::CITY,
                '%' . str_replace(' ', '%', ucwords($query)) . '%',
                'like'
            );
        }

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
     * @param string $cartId
     * @param string $pointName
     *
     * @return bool
     */
    public function savePoint($cartId, $pointName)
    {
        try {
            $quoteId = null;

            //check guest or user cart
            if (preg_match("/[a-z]/i", $cartId)) {
                $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
                $quoteId = (int) $quoteIdMask->getQuoteId();
            } else {
                $quoteId = (int) $cartId;
            }

            /** @var CartInterface $quote */
            $quote = $this->quoteFactory->create();

            $quote->getResource()->load($quote, $quoteId, CartInterface::KEY_ENTITY_ID);
            $quote->setInpostName($pointName);
            $quote->getResource()->save($quote);

            return true;
        } catch (\Exception $ex) {
            $this->logger->error(
                sprintf(
                    'Point save error. Exception Message: %s. Quote id: %d. Point name: %s.',
                    $ex->getMessage(),
                    $quoteId ?? 'undefined',
                    $pointName
                )
            );
        }

        return false;
    }
}
