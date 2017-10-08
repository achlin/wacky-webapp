<div class="row">
    <table id="flightTable">
        <tr>
            <th>Flight No.</th>
            <th>Departs From</th>
            <th>Arrives At</th>
        </tr>
        {flightSchedule}
        <tr title="{details}">
            <td>{flightNo}</td>
            <td>{departsFrom}</td>
            <td>{arrivesAt}</td>
        </tr>
        {/flightSchedule}
    </table>
</div>
