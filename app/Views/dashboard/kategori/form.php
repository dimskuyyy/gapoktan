<?php
helper(['form']);
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form Tambah Kategori</h4>
        </div>
        <?php echo form_open('#', ['class' => 'form-kategori']);
        if (isset($data)) {
            echo form_hidden('id', $data['kat_id']);
        }
        ?>
        <div class="modal-body">
            <div class="form-group">
                <label for='kategori'>Nama Kategori</label>
                <input type="text" name="kategori" class="form-control" value="<?= isset($data) ? $data['kat_nama'] : '' ?>" required id="nama">
            </div>
            <div class="form-group">
                <label for='kategori'>Status Kategori</label><br>
                <label><input type="radio" class="status" name="status" value="1" <?= isset($data) ? ($data['kat_status'] == 1 ? 'checked' : '') : 'checked' ?> required> Save as draft</label>
                <label><input type="radio" class="status" name="status" value="2" <?= isset($data) ? ($data['kat_status'] == 2 ? 'checked' : '') : '' ?> required> Publish</label>
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
    $('.form-kategori').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('.btn-submit');
        var htm = btn.html();
        setLoadingBtn(btn);
        $.ajax({
            url: base_url + '/kategori/save',
            type: 'post',
            dataType: 'json',
            data: form.serialize(),
            success: function(res) {
                if (res.status) {
                    successMsg(res.msg);
                    form[0].reset();
                    $('#table-kategori').DataTable().ajax.reload();
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