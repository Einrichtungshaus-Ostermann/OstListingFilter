<?php
/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Listing Filter
 *
 * @package   OstListingFilter
 *
 * @author    Eike Brandt-Warneke <e.brandt-warneke@ostermann.de>
 * @copyright 2018 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

namespace OstListingFilter\Bundle\SearchBundleES\ConditionHandler;

use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Search;
use OstListingFilter\Bundle\SearchBundle\Condition\SearchCondition;
use Shopware\Bundle\SearchBundle\Condition\SearchTermCondition;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaPartInterface;
use Shopware\Bundle\SearchBundleES\HandlerInterface;
use Shopware\Bundle\SearchBundleES\PartialConditionHandlerInterface;
use Shopware\Bundle\SearchBundleES\SearchTermQueryBuilderInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class SearchConditionHandler implements HandlerInterface, PartialConditionHandlerInterface
{
    /**
     * ...
     *
     * @var SearchTermQueryBuilderInterface
     */
    private $queryBuilder;

    /**
     * @param SearchTermQueryBuilderInterface $queryBuilder
     */
    public function __construct(SearchTermQueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CriteriaPartInterface $criteriaPart)
    {
        return $criteriaPart instanceof SearchCondition;
    }

    /**
     * {@inheritdoc}
     */
    public function handleFilter(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        /* @var SearchCondition $criteriaPart */
        $search->addQuery(
            $this->queryBuilder->buildQuery($context, $criteriaPart->getValue()),
            BoolQuery::FILTER
        );
    }

    /**
     * {@inheritdoc}
     */
    public function handlePostFilter(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        /* @var SearchTermCondition $criteriaPart */
        $search->addPostFilter(
            $this->queryBuilder->buildQuery($context, $criteriaPart->getTerm())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CriteriaPartInterface $criteriaPart, Criteria $criteria, Search $search, ShopContextInterface $context)
    {
        if ($criteria->hasBaseCondition($criteriaPart->getName())) {
            $this->handleFilter($criteriaPart, $criteria, $search, $context);
        } else {
            $this->handlePostFilter($criteriaPart, $criteria, $search, $context);
        }
    }
}
