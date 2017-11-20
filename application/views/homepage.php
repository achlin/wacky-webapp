<a class="btn btn-primary" data-popup-open="popup-1" href="#">Book now!</a>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 portfolio-item homecard">
        <div class="card h-100">
            <a><img class="card-img-top" src="/assets/images/ypr.jpg" alt=""></a>
            <div class="card-body">
                <h4 class="card-title">Base</h4>
                <p class="card-text">We operate from {baselocation} ({basecode})<br></p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 portfolio-item homecard">
        <div class="card h-100">
            <a><img class="card-img-top" src="/assets/images/airports.jpg" alt=""></a>
            <div class="card-body">
                <h4 class="card-title">Serviced Airports</h4>
                <p class="card-text">
                    {airports}
                    {community} ({id}) <br>
                    {/airports}
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 portfolio-item homecard">
        <div class="card h-100">
            <a><img class="card-img-top" src="/assets/images/flights.jpg" alt=""></a>
            <div class="card-body">
                <h4 class="card-title">Flights</h4>
                <p class="card-text">{flightsize} flights scheduled daily<br></p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 portfolio-item homecard">
        <div class="card h-100">
            <a><img class="card-img-top" src="/assets/images/fleet.png" alt=""></a>
            <div class="card-body">
                <h4 class="card-title">Fleet</h4>
                <p class="card-text">Growing fleet of {fleetsize} planes</p>
            </div>
        </div>
    </div>
</div>
<div class="popup" data-popup="popup-1">
    <div class="popup-inner">
        <h4>Where would you like to go?</h2>
        <form action="/booking/search" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="flyingFromSelect">Flying from</label>
                    <select class="form-control" id="flyingFromSelect" name="startAirport" required>
                        <option value="" disabled selected hidden>Choose...</option>
                        <option value={basecode}>{baselocation} ({basecode})</option>
                        {airports}
                        <option value={id}>{community} ({id})</option>
                        {/airports}
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="flyingToSelect">Flying to</label>
                    <select class="form-control" id="flyingToSelect" name="endAirport" required>
                        <option value="" disabled selected hidden>Choose...</option>
                        <option value={basecode}>{baselocation} ({basecode})</option>
                        {airports}
                        <option value={id}>{community} ({id})</option>
                        {/airports}
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary float-right">Search</button>
        </form>
        <a class="popup-close" data-popup-close="popup-1" href="#">x</a>
    </div>
</div>
