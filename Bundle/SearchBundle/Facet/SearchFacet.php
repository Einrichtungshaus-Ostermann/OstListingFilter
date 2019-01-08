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

namespace OstListingFilter\Bundle\SearchBundle\Facet;

use Shopware\Bundle\SearchBundle\FacetInterface;

class SearchFacet implements FacetInterface
{
    /**
     * ...
     *
     * @return string
     */
    public function getName()
    {
        // return name
        return 'ostlf_search';
    }
}
