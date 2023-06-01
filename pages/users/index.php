<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
        </tr>
    </thead>
    <tbody>
        <?
        $oUsers = new user();
        $arrUsers = $oUsers->get();
        foreach ($arrUsers as $arrUser) {
        ?>
            <tr>
                <th scope="row">
                    <div class="col-auto">
                        <label for="inputId<?= $arrUser['id'] ?>" class="visually-hidden">Id</label>
                        <input name="id" type="number" class="form-control-plaintext _change" id="inputId<?= $arrUser['id'] ?>" placeholder="Id" value="<?= $arrUser['id'] ?>" readonly>
                    </div>
                </th>
                <td>
                    <div class="col-auto">
                        <label for="inputName<?= $arrUser['id'] ?>" class="visually-hidden">Name</label>
                        <input name="name" type="text" class="form-control _change" id="inputName<?= $arrUser['id'] ?>" data-id="<?= $arrUser['id'] ?>" placeholder="Name" value="<?= $arrUser['name'] ?>">
                    </div>
                </td>
                <td>
                    <div class="col-auto">
                        <label for="inputEmail<?= $arrUser['id'] ?>" class="visually-hidden">Email</label>
                        <input name="email" type="text" class="form-control-plaintext _change" id="inputEmail<?= $arrUser['id'] ?>" placeholder="Email" value="<?= $arrUser['email'] ?>" readonly>
                    </div>
                </td>
                <td>
                    <div class="col-auto">
                        <label for="inputRole<?= $arrUser['id'] ?>" class="visually-hidden">Role</label>
                        <select class="form-select" value="<?= $arrUser['role'] ?>" name="role" aria-label="Default select example" disabled>
                            <option <?= (int)$arrUser['role'] == 0 ? 'selected' : '' ?> value="0">Default (0)</option>
                            <option <?= (int)$arrUser['role'] == 1 ? 'selected' : '' ?> value="1">Manager (1)</option>
                            <option <?= (int)$arrUser['role'] == 2 ? 'selected' : '' ?> value="2">Admin (2)</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="d-flex justify-content-end">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button class="btn btn-success _edit" data-id="<?= $arrUser['id'] ?>" data-table="users" data-bs-toggle="modal" data-bs-target="#exampleModal">edit</button>
                            <button class="btn btn-danger _del" data-id="<?= $arrUser['id'] ?>">del</button>
                        </div>
                    </div>
                </td>
            </tr>
        <?
        }
        ?>
    </tbody>
</table>

<div id="status"></div>

<div class="card" style="max-width: 18rem; margin: auto;">
    <div class="card-body">
        <h5 class="card-title">User add</h5>
        <form class="row g-3" id="form_add">
            <input type="hidden" name="api" value="true">
            <input type="hidden" name="table" value="users">
            <input type="hidden" name="action" value="post">
            <div class="col-auto">
                <label for="inputName" class="visually-hidden">Name</label>
                <input name="name" type="text" class="form-control" id="inputName" placeholder="Name">
            </div>
            <div class="col-auto">
                <label for="inputEmail" class="visually-hidden">Email</label>
                <input name="email" type="email" class="form-control" id="inputEmail" value="" placeholder="email@example.com">
            </div>
            <div class="col-auto">
                <select class="form-select" name="role" aria-label="Default select example">
                    <option selected value="0">Default (0)</option>
                    <option value="1">Manager (1)</option>
                    <option value="2">Admin (2)</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mb-3">Add</button>
            </div>
        </form>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal_status"></div>
                <form class="row g-3" id="form_edit">
                    <input type="hidden" name="api" value="true">
                    <input type="hidden" name="table" value="users">
                    <input type="hidden" name="action" value="put">

                    <div class="col-auto">
                        <label for="inputId" class="visually-hidden">Name</label>
                        <input name="id" type="text" readonly class="form-control" id="inputId" placeholder="id">
                    </div>
                    <div class="col-auto">
                        <label for="inputNameEdit" class="visually-hidden">Name</label>
                        <input name="name" type="text" class="form-control" id="inputNameEdit" placeholder="Name">
                    </div>
                    <div class="col-auto">
                        <label for="inputEmailEdit" class="visually-hidden">Email</label>
                        <input name="email" type="email" class="form-control" id="inputEmailEdit" value="" placeholder="email@example.com">
                    </div>
                    <div class="col-auto">
                        <select class="form-select" value="" name="role" aria-label="Default select example">
                            <option selected value="0">Default (0)</option>
                            <option value="1">Manager (1)</option>
                            <option value="2">Admin (2)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="form_edit_submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $(document).find('#form_add').on('submit', function(e) {
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
                location.reload()
            })
            return false
        })
        $(document).find('#form_edit_submit').on('click', function(e) {
            const form = $(document).find('#form_edit')
            const json = convertFormToJSON(form)
            $.ajax({
                "url": "/api/",
                "type": "POST",
                "contentType": "application/json",
                "data": JSON.stringify(json),
            }).fail(function(jqXHR, textStatus) {
                $('#modal_status').html('<div class="alert alert-warning alert-dismissible fade show" role="alert">' + jqXHR.responseJSON.message + '</div>')
            }).done(function(oData) {
                location.reload()
            })
            return false
        })
        var exampleModal = document.getElementById('exampleModal')
        exampleModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget
            var iId = button.getAttribute('data-id')
            var oTr = $(button).parents('tr')
            var modalTitle = exampleModal.querySelector('.modal-title')
            modalTitle.textContent = 'Edit user: ' + iId

            $(exampleModal).find('input[name="name"]').val(oTr.find('input[name="name"]').val())
            $(exampleModal).find('input[name="email"]').val(oTr.find('input[name="email"]').val())
            $(exampleModal).find('select[name="role"]').val(oTr.find('select[name="role"]').val())
            $(exampleModal).find('input[name="id"]').val(oTr.find('input[name="id"]').val())
        })
        $(document).find('._change').on('input', function() {
            var
                sName = $(this).attr('name'),
                sVal = $(this).val(),
                sId = $(this).data().id,
                oData = {
                    'table': 'users',
                    'action': 'patch',
                    'jwt': localStorage.getItem('jwt'),
                    'id': sId,
                }

            oData[sName] = sVal

            $.ajax({
                "url": "/api/",
                "type": "POST",
                "contentType": "application/json",
                "data": JSON.stringify(oData),
            })
            return false
        })
        $(document).find('._del').on('click', function(e) {
            $.ajax({
                "url": "/api/",
                "type": "POST",
                "contentType": "application/json",
                "data": JSON.stringify({
                    'jwt': localStorage.getItem('jwt'),
                    'table': 'users',
                    'action': 'delete',
                    'id': $(this).data().id,
                }),
            })
            $(this).parents('tr').remove()
            return false
        })
    })
</script>