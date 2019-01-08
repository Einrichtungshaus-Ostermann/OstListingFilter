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

namespace OstListingFilter\Bundle\SearchBundleDBAL\FacetHandler;

use Enlight_Components_Snippet_Namespace as SnippetNamespace;
use OstListingFilter\Bundle\SearchBundle\Condition\SearchCondition;
use OstListingFilter\Bundle\SearchBundle\Facet\SearchFacet;
use OstListingFilter\Bundle\SearchBundle\FacetResult\SearchFacetResult;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\FacetInterface;
use Shopware\Bundle\SearchBundle\FacetResultInterface;
use Shopware\Bundle\SearchBundleDBAL\PartialFacetHandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;
use Shopware_Components_Snippet_Manager as SnippetManager;

class SearchFacetHandler implements PartialFacetHandlerInterface
{
    /**
     * ...
     *
     * @var SnippetNamespace
     */
    private $snippet;

    /**
     * ...
     *
     * @param SnippetManager $snippetManager
     */
    public function __construct(SnippetManager $snippetManager)
    {
        // set params
        $this->snippet = $snippetManager->getNamespace('frontend/ost-listing-filter/facets');
    }

    /**
     * ...
     *
     * @param FacetInterface $facet
     *
     * @return bool
     */
    public function supportsFacet(FacetInterface $facet)
    {
        // return
        return  $facet instanceof SearchFacet;
    }

    /**
     * ...
     *
     * @param FacetInterface              $facet
     * @param Criteria                    $reverted
     * @param Criteria                    $criteria
     * @param Struct\ShopContextInterface $context
     *
     * @return FacetResultInterface
     */
    public function generatePartialFacet(FacetInterface $facet, Criteria $reverted, Criteria $criteria, Struct\ShopContextInterface $context)
    {
        /* @var SearchCondition $condition */
        $condition = $criteria->getCondition('ostlf_search');

        // get the value
        $value = ($condition instanceof SearchCondition)
            ? $condition->getValue()
            : '';

        // return the facet
        return new SearchFacetResult(
            $facet->getName(),
            $facet->getName(),
            $value,
            $this->snippet->get('search', 'Suchbegriff eingeben...', true)
        );
    }
}
