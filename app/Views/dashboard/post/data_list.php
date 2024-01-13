<div class="pull-left">
    <button type="button" class="btn btn-sm btn-flat btn-primary btn-create"><i class="fa fa-plus"></i> Tambah Post</button>
</div><br><br>
<table id="table-post" class="table table-bordered table-hover table-striped" style="width:100%">
    <thead>
        <tr>
            <th data-priority='1'>Judul</th>
            <th data-priority='1'>Kategori</th>
            <th data-priority='1'>Status</th>
            <th data-priority='1'>Jenis</th>
            <th data-priority='1'>Tgl. Simpan</th>
            <th data-priority='1'>Tgl. Publish</th>
            <th data-priority='1'>Tgl. Update</th>
            <th data-priority='1'></th>
        </tr>
    </thead>
</table>
<script>
    $('#table-post').on('click', 'tbody tr td .btn-copy', function(e) {
        e.preventDefault();
        let slug = $(this).attr('data-slug');
        let jenis = $(this).attr('data-jenis');
        if (slug != '') {
            let link;
            if (jenis == 1) {
                link = root_url + '<?= URL_POST_BERITA ?>' + slug;
            } else {
                link = root_url + '<?= URL_POST_HALAMAN ?>' + slug;
            }
            navigator.clipboard.writeText(link).then(function() {
                successMsg('Link berhasil di-copy');
            }, function() {
                errorMsg('Failure to copy. Check permissions for clipboard');
            });
        }
    });
    $('#table-post').on('click', 'tbody tr td .btn-edit', function(e) {
        e.preventDefault();
        var btn = $(this);
        var htm = btn.html();
        var id = btn.attr('data-id');
        if (id) {
            setLoadingBtn(btn);
            $.ajax({
                url: base_url + '/post/form',
                type: 'post',
                data: {
                    id: id
                },
                success: function(res) {
                    if (res.status == false) {
                        errorMsg(res.msg);
                        resetLoadingBtn(btn, htm);
                    } else {
                        resetLoadingBtn(btn, htm);
                        content.html(res);
                    }
                },
                error: function(xhr, status, error) {
                    errorMsg(error);
                    resetLoadingBtn(btn, htm);
                }
            });
        }
    });
    $('#table-post').on('click', 'tbody tr td .btn-delete', function(e) {
        e.preventDefault();
        var btn = $(this);
        var htm = btn.html();
        var id = btn.attr('data-id');
        if (id) {
            if (confirm('Yakin hapus post ?')) {
                setLoadingBtn(btn);
                $.ajax({
                    url: base_url + '/post/delete',
                    type: 'post',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res.status) {
                            successMsg(res.msg);
                            $('#table-post').DataTable().ajax.reload();
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
    var oTable = $('#table-post').dataTable({
        'bFilter': false,
        'autoWidth': true,
        'pagingType': 'full_numbers',
        'paging': true,
        'processing': true,
        'serverSide': true,
        'searching': true,
        'ordering': true,
        'fixedColumns': true,
        'language': {
            processing: '<i class=\'fa fa-refresh fa-spin fa-3x fa-fw\'></i><span class=\'sr-only\'>Loading...</span>'
        },
        'columns': [{
                data: 'judul',
                name: 'judul'
            },
            {
                data: 'kategori',
                name: 'kategori',
            },
            {
                'data': null,
                'searchable': false,
                render: function(data, type, row) {
                    return data.status == 1 ? '<span class=\'label label-danger\'>save as draft</label>' : '<span class=\'label label-success\'>publish</label>';
                }
            },
            {
                'data': null,
                'searchable': false,
                render: function(data, type, row) {
                    return data.jenis == 1 ? '<span class=\'label label-danger\'>berita</label>' : '<span class=\'label label-warning\'>halaman</label>';
                }
            },
            {
                data: 'tgl_simpan',
                name: 'tgl_simpan'
            },
            {
                data: 'tgl_publish',
                name: 'tgl_publish'
            },
            {
                data: 'tgl_update',
                name: 'tgl_update'
            },
            {
                'data': null,
                'render': function(data, type, row) {
                    return '<button data-jenis=\'' + data.jenis + '\' data-slug=\'' + data.slug + '\' type=\'button\' class=\'btn btn-sm btn-info btn-flat btn-copy\' title=\'klik untuk copy link post\'><i class=\'fa fa-copy\'></i></button>' +
                        ' <button data-id=\'' + data.id + '\' type=\'submit\' class=\'btn btn-sm btn-success btn-flat btn-edit\' title=\'klik untuk edit post\'><i class=\'fa fa-edit\'></i></button>' +
                        (<?= json_encode(AuthUser()->level == 1); ?> || data.penulis == <?= json_encode(AuthUser()->id); ?> ? ' <button data-id=\'' + data.id + '\' type=\'button\' class=\'btn btn-sm btn-danger btn-flat btn-delete\' title=\'klik untuk hapus post\'><i class=\'fa fa-trash\'></i></button>' : '');
                }
            },
        ],
        'ajax': function(data, callback, setting) {
            $.ajax({
                url: base_url + '/post/list',
                type: 'post',
                data: {
                    datatables: data
                },
                success: function(r) {
                    callback({
                        recordsTotal: r.recordsTotal,
                        recordsFiltered: r.recordsFiltered,
                        data: r.data
                    });
                },
                error: function(xhr, status, error) {
                    errorMsg(error);
                }
            })
        },
        'responsive': true
    });
    $('.btn-create').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var htm = btn.html();
        setLoadingBtn(btn);
        $.ajax({
            url: base_url + '/post/form',
            type: 'post',
            success: function(res) {
                resetLoadingBtn(btn, htm);
                content.empty();
                content.html(res);
            },
            error: function(xhr, status, error) {
                errorMsg(error);
                resetLoadingBtn(btn, htm);
            }
        });
    });
</script>