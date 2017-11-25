<h5 class="text-danger">{error}</h5>
<div class="row">
    <form class="container" role="form" action="{action}" method="post">
        <table class="table table-striped table-dark">
            <tbody id="plane-info-body">
                <tr>
                    <td>Plane ID:</td>
                    <td>{fid}</td>
                </tr>
                <tr>
                    <td>Airplane Code:</td>
                    <td>{fairplaneCode}</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td colspan="2" align="right">{zsubmit}<a href="/fleet/cancel"><input type="button" class="btn btn-light" value="Cancel"/></a></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
