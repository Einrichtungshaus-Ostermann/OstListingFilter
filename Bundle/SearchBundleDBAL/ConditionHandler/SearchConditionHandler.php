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

namespace OstListingFilter\Bundle\SearchBundleDBAL\ConditionHandler;

use OstListingFilter\Bundle\SearchBundle\Condition\SearchCondition;
use Shopware\Bundle\SearchBundle\Condition\SearchTermCondition;
use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundleDBAL\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use Shopware\Bundle\SearchBundleDBAL\SearchTermQueryBuilderInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SearchConditionHandler implements ConditionHandlerInterface
{
    /**
     * ...
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * ...
     *
     * @var array
     */
    private $configuration;

    /**
     * ...
     *
     * @param ContainerInterface $container
     * @param array              $configuration
     */
    public function __construct(ContainerInterface $container, array $configuration)
    {
        // ...
        $this->container = $container;
        $this->configuration = $configuration;
    }

    /**
     * ...
     *
     * @param ConditionInterface $condition
     *
     * @return bool
     */
    public function supportsCondition(ConditionInterface $condition)
    {
        // return
        return  $condition instanceof SearchCondition;
    }

    /**
     * ...
     *
     * @param ConditionInterface   $condition
     * @param QueryBuilder         $query
     * @param ShopContextInterface $context
     */
    public function generateCondition(ConditionInterface $condition, QueryBuilder $query, ShopContextInterface $context)
    {
        /** @var $condition SearchCondition */

        // get the search value
        $value = $condition->getValue();

        // do we have an empty search value?
        if (empty($value)) {
            // nothing to do
            return;
        }

        // do we want the default search?
        if ((bool) $this->configuration['shopwareSearchStatus'] === false) {
            // custom search
            $this->defaultSearch($value, $condition, $query, $context);

            // done
            return;
        }

        // shopware fulltext search
        $this->shopwareSearch($value, $condition, $query, $context);
    }

    /**
     * ...
     *
     * @param string               $value
     * @param ConditionInterface   $condition
     * @param QueryBuilder         $query
     * @param ShopContextInterface $context
     */
    private function defaultSearch(string $value, ConditionInterface $condition, QueryBuilder $query, ShopContextInterface $context)
    {
        // join supplier for the name
        $query->leftJoin(
            'product',
            's_articles_supplier',
            'ostlfSearchSupplier',
            'ostlfSearchSupplier.id = product.supplierID'
        );

        // join filter
        $query->leftJoin(
            'product',
            's_filter_articles',
            'ostlfFilterArticles',
            'ostlfFilterArticles.articleID = product.id'
        );

        // join filter values
        $query->leftJoin(
            'ostlfFilterArticles',
            's_filter_values',
            'ostlfFilterValues',
            'ostlfFilterValues.id = ostlfFilterArticles.valueID'
        );

        // we need the search string split by whitespace
        $split = explode(' ', trim($value));

        // unique alias
        $i = 0;

        // loop every single search term
        foreach ($split as $aktu) {
            // only valid
            if (empty($aktu)) {
                // next
                continue;
            }

            // unique alias
            $param = 'ostlfSearch_' . $i;

            // every search paramter
            $params = [
                '( product.name LIKE :' . $param . ' )',
                '( product.description LIKE :' . $param . ' )',
                '( variant.ordernumber LIKE :' . $param . ' )',
                '( ostlfSearchSupplier.name LIKE :' . $param . ' )',
                '
                product.id IN ( 
                    SELECT DISTINCT(subfilterarticle_' . $i . '.articleID)
                    FROM s_filter_values AS subfiltervalue_' . $i . '
                        LEFT JOIN s_filter_articles AS subfilterarticle_' . $i . '
                            ON subfiltervalue_' . $i . '.id = subfilterarticle_' . $i . '.valueID
                    WHERE subfiltervalue_' . $i . '.value LIKE :' . $param . '
                ) 
                '
            ];

            // combined with OR and every single search tearm combined with AND
            $query->andWhere('( ' . implode(' OR ', $params) . ' )');

            // set parameter for this term
            $query->setParameter($param, '%' . trim($aktu) . '%');

            // next unique alias
            ++$i;
        }
    }

    /**
     * ...
     *
     * @param string               $value
     * @param ConditionInterface   $condition
     * @param QueryBuilder         $query
     * @param ShopContextInterface $context
     */
    private function shopwareSearch(string $value, ConditionInterface $condition, QueryBuilder $query, ShopContextInterface $context)
    {
        // get the shopware search query builder
        /* @var $searchTermQueryBuilder SearchTermQueryBuilderInterface */
        $searchTermQueryBuilder = $this->container->get('shopware_searchdbal.search_query_builder_dbal');

        /* @var SearchTermCondition $condition */
        $searchQuery = $searchTermQueryBuilder->buildQuery(
            $value
        );

        // no matching products found by the search query builder
        if ($searchQuery === null) {
            // add condition that the result contains no product
            $query->andWhere('0 = 1');

            // stop here
            return;
        }

        // get the search sub query
        $queryString = $searchQuery->getSQL();

        // add search select and state
        $query->addSelect('searchTable.*');
        $query->addState('ranking');

        // join the search
        $query->innerJoin(
            'product',
            '(' . $queryString . ')',
            'searchTable',
            'searchTable.product_id = product.id'
        );
    }
}
