
{* create array for the properties *}
{assign var="propertyFacets" value=[]}

{* every facet without atsd-search *}
{assign var="defaultFacets" value=[]}



{* loop all facets *}
{foreach $facets as $facet}

    {* valid facet? *}
    {if $facet->getTemplate() !== null}

        {* is this a property? *}
        {if $facet->getFacetName() == "property"}

            {* just add it to our array *}
            {append var='propertyFacets' value=$facet}

        {else}

            {* output the filter first *}
            {if $facet->getFacetName() == "ostlf_search"}

                {* output first *}
                {include file=$facet->getTemplate() facet=$facet}

            {else}

                {* just add it to our array *}
                {append var='defaultFacets' value=$facet}

            {/if}

        {/if}

    {/if}

{/foreach}



{* loop every default *}
{foreach $defaultFacets as $facet}

    {* output it *}
    {include file=$facet->getTemplate() facet=$facet}

{/foreach}



{* loop every property *}
{foreach $propertyFacets as $facet}

    {* output it *}
    {include file=$facet->getTemplate() facet=$facet}

{/foreach}
