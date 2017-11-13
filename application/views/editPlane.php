<div class="card">
    <div class="card-body">
    <h4 class="card-title">Edit Airplane</h4>
        <form class="card-text" role="form" action="/fleet/submit" method="post">
            {id}
            {airplaneCode}
            <hr/>
            <input type="submit" text="Submit"/>
        </form>
        <h6 class="text-danger">{error}</h6>
    </div>
</div>
