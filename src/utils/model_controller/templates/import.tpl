{$form}

<h3>{$import_message}</h3>
<p>
    <table class="import-table">
        <thead><tr>{foreach from=$import_headers item=header}<td>{$header}</td>{/foreach}</tr></thead>
        <tbody>
        {foreach from=$import_status item=status}
            <tr>
                {foreach from=$status.data item=cell key=key }
                    <td>{$cell}</td>
                {/foreach}
            </tr>
        {/foreach}
        </tbody>
    </table>
</p>
