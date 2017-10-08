<div class="row">
    <table class="table table-striped table-inverse" id="flightTable">
        <thead>
            <tr>
                <th>Flight No.</th>
                <th>Departs From</th>
                <th>Arrives At</th>
            </tr>
        </thead>
        <tbody>
            {flightSchedule}
                <tr title="{details}">
                    <td>{flightNo}</td>
                    <td>{departsFrom}</td>
                    <td>{arrivesAt}</td>
                </tr>
            {/flightSchedule}
        </tbody>
    </table>
</div>
