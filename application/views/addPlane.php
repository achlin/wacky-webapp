<div class="card">
    <div class="card-body">
    <h4 class="card-title">Add Airplane</h4>
        <form class="card-text" role="form" action="/fleet/submitAdd" method="post">
            {id}
            {airplaneCode}
            <hr/>
            <input type="submit" value="Submit"/>
        </form>
        <h6 class="text-danger">{error}</h6>
    </div>
</div>
