<div class="row">
    <table class="table table-striped table-inverse" id="fleetTable">
        <thead>
            <tr>
                <th>Manufacturer</th>
                <th>Model</th>
            </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>
</div>
