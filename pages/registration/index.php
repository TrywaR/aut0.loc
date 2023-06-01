<div class="card" style="max-width: 18rem; margin: 2rem auto;">
    <div class="card-body">
        <h5 class="card-title">Registration</h5>
        <div id="status"></div>
        <form id="registraton_form">
            <input type="hidden" name="table" value="authorization">
            <input type="hidden" name="action" value="registration">
            <div class="mb-3">
                <label for="exampleInputName" class="form-label">Name</label>
                <input name="name" type="text" class="form-control" id="exampleInputName" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input name="password" type="password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword2" class="form-label">Repeat password</label>
                <input name="password2" type="password" class="form-control" id="exampleInputPassword2">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <script>
            $(function() {
                $(document).find('#registraton_form').on('submit', function(e) {
                    const form = $(e.target)
                    const json = convertFormToJSON(form)
                    $.ajax({
                        "url": "/api/",
                        "type": "POST",
                        "contentType": "application/json",
                        "data": JSON.stringify(json),
                    }).fail(function(jqXHR, textStatus) {
                        $('#status').html('<div class="alert alert-warning alert-dismissible fade show" role="alert">' + jqXHR.responseJSON.message + '</div>')
                    }).done(function(oData) {
                        location = '/login/'
                    })
                    return false
                })
            })
        </script>
    </div>
</div>