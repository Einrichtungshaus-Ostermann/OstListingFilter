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

namespace OstListingFilter\Listeners\Controllers\Frontend;

use Enlight_Event_EventArgs as EventArgs;
use Shopware\Models\Attribute\Category as Attribute;
use Shopware\Models\Category\Category;
use Shopware_Controllers_Frontend_Listing as Controller;

class Listing
{
    /**
     * ...
     *
     * @var string
     */
    private $viewDir;

    /**
     * ...
     *
     * @param string $viewDir
     */
    public function __construct($viewDir)
    {
        // set params
        $this->viewDir = $viewDir;
    }

    /**
     * ...
     *
     * @param EventArgs $arguments
     */
    public function onPreDispatch(EventArgs $arguments)
    {
        // get the controller
        /* @var $controller Controller */
        $controller = $arguments->get('subject');

        // get parameters
        $view = $controller->View();
        $request = $controller->Request();

        // ...
        $configuration = Shopware()->Container()->get('ost_listing_filter.configuration');

        // get category id
        $categoryId = (int) $request->getParam('sCategory', 0);

        // get the category
        /* @var $category Category */
        $category = Shopware()->Container()->get('models')->find(Category::class, $categoryId);

        // set hide filter
        $hide = ($category instanceof Category)
            ? ((count($category->getChildren()) === 0)
                ? $category->getHideFilter()
                : (($configuration['disableParentCategories'] === false) ? $category->getHideFilter() : ((($category->getAttribute() instanceof Attribute) && ($category->getAttribute()->getOstListingFilterShowFilter() === true)) ? false : true)))
            : false;

        // assign it to the view
        $view->assign('atsdListingFilterHideFilter', $hide);
        $view->assign('atsdListingFilterConfiguration', $configuration);

        // add template dir
        $view->addTemplateDir($this->viewDir);
    }
}
