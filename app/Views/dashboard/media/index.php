<?= $this->extend('dashboard/layout/back_main') ?>

<?= $this->section('title') ?>
Media
<?= $this->endsection() ?>

<?= $this->section('header') ?>
<section class="content-header">
    <h1>
        Media
    </h1>
</section>
<?= $this->endsection() ?>

<?= $this->section('content') ?>
<div class="box box-widget">
    <div class="box-body">
        <a href="#" class="btn btn-sm btn-flat btn-primary btn-create" style="margin-bottom: 2rem;" data-backdrop="static">
            <i class="fa fa-plus"></i> Tambah Media
        </a>
        <div class="pull-right">
            <div class="input-group">
                <input type="text" class="form-control" id="keyword" placeholder="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" disabled>Go!</button>
                </span>
            </div><!-- /input-group -->
        </div><br><br>
        <div class="list-data" style="margin-top: 2rem;">
            <div id="media-list"></div>
        </div>
    </div>
</div>
<?= $this->endsection() ?>
<?= $this->section('js') ?>
<script>
    const media = $('#media-list');

    function loadData(page, keyword = null) {
        $.ajax({
            url: base_url + '/media/list',
            type: 'post',
            data: {
                type: 1,
                page: page,
                keyword: keyword
            },
            success: function(data) {
                resetLoadingBtn(media);
                media.html(data);
            },
            error: function(xhr, status, error) {
                errorMsg(error);
                resetLoadingBtn(media);
            }
        });
    }
    $(document).ready(function() {
        let keyword = $('#keyword').val();

        const viewList = debounce((page, keyword) => {
            loadData(page, keyword)
        }, 800, media);

        loadData(1);
        $(document).on('click', '.pagination a', function(e) {
            if ($(this).attr('href')) {
                e.preventDefault();
                let page = $(this).attr('href');
                page = page.split('=');
                loadData(page[1], keyword);
            }
        });

        $('#keyword').on('input', function() {
            const UpdatedKey = $(this).val();
            keyword = UpdatedKey;
            viewList(1, UpdatedKey);
        });





        $('.btn-create').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var htm = btn.html();
            setLoadingBtn(btn);
            $.ajax({
                url: base_url + '/media/form',
                type: 'post',
                success: function(res) {
                    resetLoadingBtn(btn, htm);
                    $('.mymodal').html(res).modal('show');
                    $('#btn-copy').hide();
                    $('#btn-edit').hide();
                    $('#btn-delete').hide();
                },
                error: function(xhr, status, error) {
                    errorMsg(error);
                    resetLoadingBtn(btn, htm);
                }
            });
        });

        $(document).on('click', '#btn-copy-thumb', function(e) {
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

        $('#media-list').on('click', '.media-detail,#btn-detail-thumb', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            if (id) {
                $.ajax({
                    url: base_url + '/media/form',
                    type: 'post',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res.status == false) {
                            errorMsg(res.msg);
                        } else {
                            $('.mymodal').html(res).modal('show');
                            $('#nama').attr('disabled', true);
                            $('#imageInput').remove();
                            $('.btn-submit').remove();
                            $('#myModalLabel').html('Form Show Media');

                        }
                    },
                    error: function(xhr, status, error) {
                        errorMsg(error);
                    }
                });
            }
        });
    });
</script>
<?= $this->endsection() ?>


<?= $this->section('plugin_css') ?>
<?php datatableCss() ?>
<?= $this->endsection() ?>

<?= $this->section('plugin_js') ?>
<?php datatableJs() ?>
<?= $this->endsection() ?>