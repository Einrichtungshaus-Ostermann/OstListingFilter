
{* file to extend *}
{extends file='parent:frontend/listing/listing_actions.tpl'}



{* we need to set a hide-filter flag if the current category is a non-leaf category *}
{block name="frontend_listing_actions_top_hide_detection"}

    {* prepend smarty *}
    {$smarty.block.parent}

    {* hide the filter? *}
    {if $ostListingFilterHideFilter == true}

        {* append the hidden class *}
        {$class = "{$class} ost-listing-filter--hide-filter"}

    {/if}

{/block}



{* add an extra sorting for high resolution *}
{block name='frontend_listing_actions_top'}

    {* prepend parent *}
    {$smarty.block.parent}

    {* we need to fix the float and enclose it with a border *}
    <div class="block-group listing--actions ost-listing-filter--sorting">
        {include file='frontend/listing/actions/action-sorting.tpl'}
    </div>

{/block}
