<div class="row">
    <table id="fleetTable">
        <tr>
            <th>Manufacturer</th>
            <th>Model</th>
        </tr>
        {fleet}
        <tr>
            <td>
                {manufacturer}
            </td>
            <td>
                <a href="/fleet/show/{key}">{model}</a>
            </td>
        </tr>
        {/fleet}
</table>
</div>
