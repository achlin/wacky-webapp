<h4 class="text-white">Flights from {departureCity}({departsFrom}) to {arrivalCity}({arrivesAt})</h4>
{availableFlights}
<div class="row">
    <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th>Available Flights with {NoOfStops} stop(s)</th>
            </tr>
        </thead>
        <tbody>
            {flights}
            <tr data-toggle="collapse" href="#{flightPathId}">
                <td>
                    <div>
                        <i class="fa fa-globe" aria-hidden="true"></i> {cityPath}
                    </div>

                </td>
                <td>
                    <div>
                        <i class="fa fa-clock-o" aria-hidden="true"></i> {totalDepartureDate} <i class="fa fa-minus" aria-hidden="true"></i> {totalArrivalTime}
                    </div>
                </td>
                <td>
                    <div class="float-right">
                        {totalTime} <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </div>
                </td>
            </tr>
            <tr id="{flightPathId}" class="collapse">
                <td colspan="3">
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
