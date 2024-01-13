<?= $this->extend('dashboard/layout/back_main') ?>

<?= $this->section('title') ?>
Post
<?= $this->endsection() ?>

<?= $this->section('header') ?>
<section class="content-header">
    <h1>
        Post
    </h1>
</section>
<?= $this->endsection() ?>

<?= $this->section('content') ?>
<div class="box box-widget">
    <div class="box-body">

    </div>
</div>
<?= $this->endsection() ?>

<?= $this->section('js') ?>

<script>
    const content = $('.box-body');
    const useDarkMode = window.matchMedia('(prefers-color-scheme: default)').matches;
    const isSmallScreen = window.matchMedia('(max-width: 1023.5px)').matches;
    const media = $('.mymodal #media-list');

    const viewList = debounce((page, keyword) => {
        loadMedia(page, keyword)
    }, 800, $('.mymodal #media-list'));

    function loadData() {
        $.ajax({
            url: base_url + '/post/datatable',
            type: 'post',
            cache: true,
            success: function(data) {
                resetLoadingBtn(content);
                content.html(data);
            },
            error: function(xhr, status, error) {
                errorMsg(error);
                resetLoadingBtn(content);
            }
        });
    }

    function loadMedia(page, keyword = null) {
        $.ajax({
            url: base_url + '/media/list',
            type: 'post',
            data: {
                page: page,
                keyword: keyword,
                type: 2,
                perPage: 6
            },
            success: function(data) {
                resetLoadingBtn($('.mymodal #media-list'));
                $('.mymodal #media-list').html(data);
            },
            error: function(xhr, status, error) {
                errorMsg(error);
                resetLoadingBtn($('.mymodal #media-list'));
            }
        });
    }

    function callMedia(call, id, btn, htm, callback = () => {}) {
        if (id) {
            setLoadingBtn(btn);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='X-CSRF-TOKEN']").attr('content')
                }
            });
            $.ajax({
                url: base_url + '/post/detail',
                type: 'post',
                data: {
                    id: id
                },
                success: function(res) {
                    if (call == 'cover') {
                        $('#media-id').val(res.data.media_id);
                        $('#media-source').removeClass('no-source');
                        $('#media-source').attr('src', root_url + 'media/' + res.data.media_slug);
                        $('.note-media').addClass('active');
                    } else if (call == 'tinymce') {
                        callback(root_url + 'media/' + res.data.media_slug, {
                            alt: res.data.media_nama
                        });
                    }
                    resetLoadingBtn(btn, htm);
                    $('.mymodal').modal('hide');
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

    function submitMedia(form, formData, btn, htm, call, callback = () => {}) {
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
                    if (call == 'cover') {
                        $('#media-id').val(res.id);
                        $('#media-source').removeClass('no-source');
                        $('#media-source').attr('src', root_url + 'media/' + res.data.media_slug);
                        $('.note-media').addClass('active');
                    } else if (call == 'tinymce') {
                        callback(root_url + 'media/' + res.data.media_slug, {
                            alt: res.data.media_nama
                        });
                    }
                    $('.mymodal').modal('hide');
                } else {
                    resetLoadingBtn(btn, htm);
                    errorMsg(res.message.msg);
                }
                resetLoadingBtn(btn, htm);
                form[0].reset();
            },
            error: function(xhr, status, error) {
                resetLoadingBtn(btn, htm);
                errorMsg(error);
            }
        })
    }

    $(document).ready(function() {
        loadData();

        $(document).on('click', '.mymodal .pagination a', function(e) {
            let keyword = $('.mymodal #keyword').val();
            if ($(this).attr('href')) {
                e.preventDefault();
                let page = $(this).attr('href');
                page = page.split('=');
                loadMedia(page[1], keyword);
            }
        });

        $(document).on('input', '.mymodal #keyword', function() {
            const UpdatedKey = $(this).val();
            viewList(1, UpdatedKey);
        });

        $(document).on('click', '.modal-content .modal-body .tab-content #media-list .row .media', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let btn = $(this).find('#insert-media');
            let htm = btn.html();
            let id = $(this).data('id');
            let call = $('.mymodal .modal-dialog').data('call');
            if (call == 'cover') {
                callMedia(call, id, btn, htm);
            }
        });

        $('.mymodal').submit('.form-media', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let call = $('.mymodal .modal-dialog').data('call');
            if (call == 'cover') {
                let form = $('.mymodal .form-media');
                let formData = new FormData($('.mymodal .form-media')[0]);
                let btn = form.find('.btn-submit');
                let htm = btn.html();
                setLoadingBtn(btn);
                submitMedia(form, formData, btn, htm, call);
            }
        });
    });
</script>
<?= $this->endsection() ?>

<?= $this->section('plugin_css') ?>
<?php
datatableCss();
select2Css();
?>
<?= $this->endsection() ?>

<?= $this->section('plugin_js') ?>
<?php
datatableJs();
select2Js();
tinymceJS(); ?>
<?= $this->endsection() ?>