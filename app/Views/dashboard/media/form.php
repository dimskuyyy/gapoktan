<?php
helper(['form']);
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form Tambah Media</h4>
        </div>
        <?php echo form_open_multipart('#', ['class' => 'form-media']);
        if (isset($data)) {
            echo form_hidden('id', $data['media_id']);
            echo form_hidden('oldMedia', $data['media_path']);
            echo form_hidden('oldSlug', $data['media_slug']);
        }
        ?>
        <div class="modal-body">
            <div class="form-group">
                <label for='nama'>Nama Media</label>
                <input type="text" name="nama" class="form-control" value="<?= isset($data) ? $data['media_nama'] : '' ?>" id="nama" required>
            </div>
            <div class="form-group">
                <label for="image">Media</label>
                <div id="imagePreview">
                    <?php if (isset($data)) : ?>
                        <img id="preview" src="<?= base_url('media/' . $data['media_slug']) ?>" alt="Preview" class="img-thumbnail preview">
                    <?php else : ?>
                        <img id="preview" src="#" alt="Preview" class="img-thumbnail preview hidden">
                    <?php endif ?>
                </div>
                <input type="file" name="path" accept="image/png, image/jpeg, image/jpg" id="imageInput">
            </div>


        </div>
        <div class="modal-footer">
            <div class="col col-sm-12">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
                <button type="button" id="btn-copy" data-slug="<?= isset($data) ? $data['media_slug'] : '' ?>" class="btn btn-warning"><i class="fa fa-link"></i> Copy</button>
                <?php if (AuthUser()->level == 1 || isset($data) && $data['media_created_by'] == AuthUser()->id) : ?>
                    <button type="button" id="btn-edit" data-id="<?= isset($data) ? $data['media_id'] : '' ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</button>
                    <button type="button" id="btn-delete" data-id="<?= isset($data) ? $data['media_id'] : '' ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-save"></i> Simpan</button>

            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    $('.modal-dialog').on('click', '.modal-content .modal-footer .col #btn-copy', function(e) {
        e.preventDefault();
        var slug = $(this).attr('data-slug');
        if (slug != '') {
            var link = root_url + '<?= URL_POST_MEDIA ?>' + slug;
            navigator.clipboard.writeText(link).then(function() {
                successMsg('Link berhasil di-copy');
            }, function() {
                errorMsg('Failure to copy. Check permissions for clipboard');
            });
        }
    });
    $('.modal-dialog').on('click', '.modal-content .modal-footer .col #btn-edit', function(e) {
        e.preventDefault();
        var btn = $(this);
        var htm = btn.html();
        var id = btn.attr('data-id');
        if (id) {
            setLoadingBtn(btn);
            $.ajax({
                url: base_url + '/media/form',
                type: 'post',
                data: {
                    id: id
                },
                success: function(res) {
                    if (res.status == false) {
                        resetLoadingBtn(btn, htm);
                        errorMsg(res.msg);
                    } else {
                        resetLoadingBtn(btn, htm);
                        $('.mymodal').html(res).modal('show');
                        $('#myModalLabel').html('Form Edit Media');
                        $('#btn-edit').hide();
                        $('#btn-copy').hide();
                        $('#btn-delete').hide();
                    }
                },
                error: function(xhr, status, error) {
                    let response = JSON.parse(xhr.responseText);
                    let errorMessage = response.msg;
                    errorMsg(errorMessage);
                    resetLoadingBtn(btn, htm);
                }
            });
        }
    });
    $('.modal-dialog').on('click', '.modal-content .modal-footer .col #btn-delete', function(e) {
        e.preventDefault();
        var btn = $(this);
        var htm = btn.html();
        var id = btn.attr('data-id');
        if (id) {
            if (confirm('Yakin hapus media ?')) {
                setLoadingBtn(btn);
                $.ajax({
                    url: base_url + '/media/delete',
                    type: 'post',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res.status) {
                            $('.mymodal').html(res).modal('hide');
                            successMsg(res.msg);
                            console.log(res.data);
                            loadData(1);
                        } else {
                            errorMsg(res.msg);
                        }
                        resetLoadingBtn(btn, htm);
                    },
                    error: function(xhr, status, error) {
                        let response = JSON.parse(xhr.responseText);
                        let errorMessage = response.msg;
                        errorMsg(errorMessage);
                        resetLoadingBtn(btn, htm);
                    }
                });
            }
        }
    });
    $("#imageInput").change(function() {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').removeClass('hidden');
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $('.form-media').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        let formData = new FormData($('.form-media')[0]);
        var btn = form.find('.btn-submit');
        var htm = btn.html();
        setLoadingBtn(btn);
        $.ajax({
            url: base_url + '/media/save',
            type: 'post',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success: function(res) {
                if (res.message.status) {
                    successMsg(res.message.msg);
                    form[0].reset();
                    <?php if (!isset($data)) { ?>
                        $('#preview').addClass('hidden');
                    <?php } ?>;
                    loadData(1);
                } else {
                    errorMsg(res.message.msg);
                }
                resetLoadingBtn(btn, htm);
            },
            error: function(xhr, status, error) {
                resetLoadingBtn(btn, htm);
                let response = JSON.parse(xhr.responseText);
                let errorMessage = response.msg;
                errorMsg(errorMessage);
            }
        })
    });
</script>