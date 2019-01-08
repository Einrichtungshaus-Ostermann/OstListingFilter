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

namespace OstListingFilter\Bundle\SearchBundle\Condition;

use Shopware\Bundle\SearchBundle\ConditionInterface;

class SearchCondition implements ConditionInterface
{
    /**
     * ...
     *
     * @var string
     */
    private $value;

    /**
     * ...
     *
     * @param string $value
     */
    public function __construct($value)
    {
        // ...
        $this->value = trim($value);
    }

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

    /**
     * ...
     *
     * @return string
     */
    public function getValue()
    {
        // return name
        return $this->value;
    }
}
