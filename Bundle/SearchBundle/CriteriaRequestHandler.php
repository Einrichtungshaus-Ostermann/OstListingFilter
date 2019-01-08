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

namespace OstListingFilter\Bundle\SearchBundle;

use Enlight_Controller_Request_RequestHttp as Request;
use OstListingFilter\Bundle\SearchBundle\Condition\SearchCondition;
use OstListingFilter\Bundle\SearchBundle\Facet\SearchFacet;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaRequestHandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class CriteriaRequestHandler implements CriteriaRequestHandlerInterface
{
    /**
     * ...
     *
     * @var array
     */
    protected $configuration;

    /**
     * ...
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        // set params
        $this->configuration = $configuration;
    }

    /**
     * ...
     *
     * @param Request              $request
     * @param Criteria             $criteria
     * @param ShopContextInterface $context
     */
    public function handleRequest(Request $request, Criteria $criteria, ShopContextInterface $context)
    {
        // are we filtering for pseudo price?
        if ($request->has('ostlf_search')) {
            // add the condition
            $criteria->addCondition(
                new SearchCondition($request->get('ostlf_search'))
            );
        }

        // pseudo price active?!
        if ((bool) $this->configuration['searchStatus']) {
            // add facet
            $criteria->addFacet(
                new SearchFacet()
            );
        }
    }
}
