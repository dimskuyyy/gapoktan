<?php
helper(['form']);
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form komentar</h4>
        </div>
        <?php echo form_open('#', ['class' => 'form-komentar']);
        if (isset($data)) {
            echo form_hidden('id', $data['komen_id']);
        }
        ?>
        <div class="modal-body">
            <div class="form-group" id="nama-group">
                <label for='komentar' class="label-name">Nama</label>
                <input type="text" class="form-control" value="<?= isset($data) ? $data['komen_nama'] : '' ?>" required id="nama">
            </div>
            <div class="form-group" id="post-group">
                <label for='komentar' class="label-post">Post</label>
                <input type="text" class="form-control" value="<?= isset($data) ? $data['post_judul'] : '' ?>" required id="post">
            </div>
            <div class="form-group">
                <label for='status'>Status Komentar</label><br>
                <label><input type="radio" class="status" name="status" value="1" <?= isset($data) ? ($data['komen_status'] == 1 ? 'checked' : '') : 'checked' ?> required> Not Approve</label>
                <label><input type="radio" class="status" name="status" value="2" <?= isset($data) ? ($data['komen_status'] == 2 ? 'checked' : '') : '' ?> required> Approve</label>
            </div>
            <div class="form-group" id="komentar-group">
                <label for="komen" class="label-komen">Komentar</label><br>
                <textarea id="komen" style=" width: 100%;" rows="10"><?= isset($data) ? $data['komen_komentar'] : '' ?></textarea>
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
    $('.form-komentar').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('.btn-submit');
        var htm = btn.html();
        setLoadingBtn(btn);
        $.ajax({
            url: base_url + '/komentar/save',
            type: 'post',
            dataType: 'json',
            data: form.serialize(),
            success: function(res) {
                if (res.status) {
                    successMsg(res.msg);
                    form[0].reset();
                    $('#table-komentar').DataTable().ajax.reload();
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