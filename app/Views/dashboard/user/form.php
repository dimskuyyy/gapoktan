<?php
helper(['form']);
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form Tambah User</h4>
        </div>
        <?php echo form_open('#', ['class' => 'form-user']);
        if (isset($data)) {
            echo form_hidden('id', $data['user_id']);
        }
        ?>
        <div class="modal-body">
            <div class="form-group" id="form-nama">
                <label for='nama'>Nama User</label>
                <input type="text" name="nama" class="form-control" value="<?= isset($data) ? $data['user_nama'] : '' ?>" required id="nama">
            </div>
            <div class="form-group" id="form-username">
                <label for='username'>Username</label>
                <input type="text" name="username" class="form-control" value="<?= isset($data) ? $data['user_username'] : '' ?>" required id="username">
            </div>
            <div class="form-group" id="form-password">
                <label for='password'>Password</label>
                <input type="password" name="password" class="form-control" required id="password">
            </div>
            <div class="form-group" id="form-confirm">
                <label for='password'>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required id="confirm_password">
            </div>
            <div class="form-group" id="form-level">
                <label for='level'>Level User</label><br>
                <label><input type="radio" class="level" name="level" value="1" <?= isset($data) ? ($data['user_level'] == 1 ? 'checked' : '') : 'checked' ?> required> Admin</label>
                <label><input type="radio" class="level" name="level" value="2" <?= isset($data) ? ($data['user_level'] == 2 ? 'checked' : '') : '' ?> required> Writer</label>
            </div>
            <div class="form-group" id="form-status">
                <label for='status'>Status User</label><br>
                <label><input type="radio" class="status" name="status" value="1" <?= isset($data) ? ($data['user_status'] == 1 ? 'checked' : '') : 'checked' ?> required> Tidak Aktif</label>
                <label><input type="radio" class="status" name="status" value="2" <?= isset($data) ? ($data['user_status'] == 2 ? 'checked' : '') : '' ?> required> Aktif</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-save"></i> Simpan</button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    $('.form-user').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('.btn-submit');
        var htm = btn.html();
        setLoadingBtn(btn);
        $.ajax({
            url: base_url + '/user/save',
            type: 'post',
            dataType: 'json',
            data: form.serialize(),
            success: function(res) {
                if (res.status) {
                    successMsg(res.msg);
                    form[0].reset();
                    $('#password').val('');
                    $('#confirm_password').val('');
                    $('#table-user').DataTable().ajax.reload();
                    location.reload()
                } else {
                    errorMsg(res.msg);
                }
                resetLoadingBtn(btn, htm);
            },
            error: function(xhr, status, error) {
                resetLoadingBtn(btn, htm);
                errorMsg(error);
            }
        })
    });
</script>