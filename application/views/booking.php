{availableFlights}
<div class="row">
    <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th>Available Flights from {departsFrom} to {arrivesAt} with {NoOfStops} stop(s)</th>
            </tr>
        </thead>
        <tbody>
            {flights}
            <tr data-toggle="collapse" href="#{flightPathId}">
                <td>
                    {segmentIds}
                    <i class="fa fa-caret-down float-right" aria-hidden="true"></i>
                </td>
            </tr>
            <tr id="{flightPathId}" class="collapse">
                <td>
                    <table class="table-bordered" id="bookingFlightTable">
                        <thead>
                            <tr>
                                <th>Flight No.</th>
                                <th>Departs From</th>
                                <th>Departure Time</th>
                                <th>Arrives At</th>
                                <th>Arrival Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            {flight}
                            <tr>
                                <td>{id}</td>
                                <td>{departsFrom}</td>
                                <td>{departureTime}</td>
                                <td>{arrivesAt}</td>
                                <td>{arrivalTime}</td>
                            </tr>
                            {/flight}
                        </tbody>
                    </table>
                </td>
            </tr>
            {/flights}
        </tbody>
    </table>
</div>
{/availableFlights}
