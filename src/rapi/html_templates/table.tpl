{if $as_totals_box eq true}
    <table>
    <tr>{foreach from=$data item=total key=i}<td>{$total}</td>{/foreach}</tr>
    </table>
{else}
    <table class="rapi-table">
        <thead>
            <tr>{foreach from=$headers item=header key=i}<th class='{if $types[$i] neq ''}rapi-column-{$types[$i]}{/if}'>{$header|replace:"\\n":"<br/>"}</th>{/foreach}</tr>
        </thead>
        <tbody>
            {foreach from=$data item=row}
                <tr>
                {foreach from=$row item=column key=i}
                    <td class="{if $types[$i] neq ''}rapi-column-{$types[$i]}{/if}">
                    {if $types[$i] eq 'double' or $types[$i] eq 'number'}
                    {$column|number_format:2}
                    {else}
                    {$column}
                    {/if}
                    </td>
                {/foreach}
                </tr>    
            {/foreach}
            {if $auto_totals eq true}
                <tr class='rapi-total-row'>
                {foreach from=$totals item=column key=i}
                    <td class='{if $types[$i] neq ''}rapi-column-{$types[$i]}{/if}'>                    
                    {if $types[$i] eq 'double' or $types[$i] eq 'number'}
                    {$column|number_format:2}
                    {else}
                    {$column}
                    {/if}
                    </td>
                {/foreach}
                </tr>
            {/if}
        </tbody>
    </table>
{/if}
