<h5 class="text-danger">{error}</h5>
<div class="row">
    <form class="container" role="form" action="/flights/submit" method="post">
        <table class="table table-striped table-dark">
            <tbody>
                <tr>
                    <td>Flight No.:</td>
                    <td>{fid}</td>
                </tr>
                <tr>
                    <td>Departs From:</td>
                    <td>{fdepartsFrom}</td>
                </tr>
                <tr>
                    <td>Arrives At:</td>
                    <td>{farrivesAt}</td>
                </tr>
                <tr>
                    <td>Departure Time:</td>
                    <td>{fdepartureTime}</td>
                </tr>
                <tr>
                    <td>Arrival Time:</td>
                    <td>{farrivalTime}</td>
                </tr>
                <tr>
                    <td>Plane:</td>
                    <td>{fplane}</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td colspan="2" align="right">{zsubmit}<a href="/flights/cancel"><input type="button" class="btn btn-light" value="Cancel"/></a></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
