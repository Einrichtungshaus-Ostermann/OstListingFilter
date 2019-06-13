<?php declare(strict_types=1);

/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Listing Filter
 *
 * @package   OstListingFilter
 *
 * @author    Eike Brandt-Warneke <e.brandt-warneke@ostermann.de>
 * @copyright 2018 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

namespace OstListingFilter\Bundle\SearchBundleES\FacetHandler;

use ONGR\ElasticsearchDSL\Search;
use OstListingFilter\Bundle\SearchBundle\Condition\SearchCondition;
use OstListingFilter\Bundle\SearchBundle\Facet\SearchFacet;
use OstListingFilter\Bundle\SearchBundle\FacetResult\SearchFacetResult;
use Shopware\Bundle\SearchBundle\Condition\SearchTermCondition;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaPartInterface;
use Shopware\Bundle\SearchBundle\ProductNumberSearchResult;
use Shopware\Bundle\SearchBundleES\HandlerInterface;
use Shopware\Bundle\SearchBundleES\ResultHydratorInterface;
use Shopware\Bundle\SearchBundleES\SearchTermQueryBuilderInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use Shopware_Components_Snippet_Manager as SnippetManager;

class SearchFacetHandler implements HandlerInterface, ResultHydratorInterface
{

    /**
     * ...
     *
     * @var SearchTermQueryBuilderInterface
     */
    private $queryBuilder;

    /**
     * ...
     *
     * @var \Enlight_Components_Snippet_Namespace
     */
    private $snippet;

    /**
     * @param SearchTermQueryBuilderInterface $queryBuilder
     * @param SnippetManager $snippetManager
     */
    public function __construct(SearchTermQueryBuilderInterface $queryBuilder, SnippetManager $snippetManager)
    {
        $this->queryBuilder = $queryBuilder;
        $this->snippet = $snippetManager->getNamespace('frontend/ost-listing-filter/facets');
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CriteriaPartInterface $criteriaPart)
    {
        return $criteriaPart instanceof SearchFacet;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CriteriaPartInterface $criteriaPart, Criteria $criteria, Search $search, ShopContextInterface $context)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate(array $elasticResult, ProductNumberSearchResult $result, Criteria $criteria, ShopContextInterface $context)
    {
        /* @var SearchCondition $condition */
        $condition = $criteria->getCondition('ostlf_search');

        // get the value
        $value = ($condition instanceof SearchCondition)
            ? $condition->getValue()
            : '';

        // create the facet
        $facet = new SearchFacetResult(
            'ostlf_search',
            'ostlf_search',
            $value,
            $this->snippet->get('search', 'Suchbegriff eingeben...', true)
        );

        // and add it
        $result->addFacet($facet);
    }
}
