/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Listing Filter
 *
 * @package   OstListingFilter
 *
 * @author    Eike Brandt-Warneke <e.brandt-warneke@ostermann.de>
 * @copyright 2018 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

;(function($) {

    // our plugin
    $.plugin( "ostListingFilter", {

        // on initialization
        init: function ()
        {
            // open the filter at high resolution
            if ( StateManager.isCurrentState( [ "xl" ] ) )
            {
                // show the facet and active filter container
                $( '*[data-listing-actions="true"] form#filter div.filter--facet-container').show();
                $( '*[data-listing-actions="true"] form#filter div.filter--active-container').attr( "style", "display: block !important;" );
            }
        },

        // on destroy
        destroy: function()
        {
            // get this
            var me = this;

            // call the parent
            me._destroy();
        }

    });

    // call our plugin
    $( '*[data-listing-actions="true"]' ).ostListingFilter();

})(jQuery);
