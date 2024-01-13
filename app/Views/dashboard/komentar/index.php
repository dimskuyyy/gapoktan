<?= $this->extend('dashboard/layout/back_main') ?>

<?= $this->section('title') ?>
Komentar
<?= $this->endsection() ?>

<?= $this->section('header') ?>
<section class="content-header">
    <h1>
        Komentar
    </h1>
</section>
<?= $this->endsection() ?>

<?= $this->section('content') ?>
<div class="box box-widget">
    <div class="box-body">
        <br>
        <table id="table-komentar" class="table table-bordered table-hover table-striped" style="width:100%">
            <thead>
                <tr>
                    <th data-priority='1'>Nama</th>
                    <th data-priority='1'>Status</th>
                    <th data-priority='1' style="max-width: 30%;">Isi Komentar</th>
                    <th data-priority='1'>Tgl. Simpan</th>
                    <th data-priority='1'>Tgl. Update</th>
                    <th data-priority='1'></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?= $this->endsection() ?>

<?= $this->section('js') ?>
<script>
    $('#table-komentar').on('click', 'tbody tr td .btn-edit', function(e) {
        e.preventDefault();
        var btn = $(this);
        var htm = btn.html();
        var id = btn.attr('data-id');
        if (id) {
            setLoadingBtn(btn);
            $.ajax({
                url: base_url + '/komentar/form',
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
                        $('.mymodal').html(res).modal('show');
                        $('#myModalLabel').html('Form Edit Komentar');
                        $('#nama-group').remove();
                        $('#post-group').remove();
                        $('#komentar-group').remove();
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
    $('#table-komentar').on('click', 'tbody tr td .btn-show', function(e) {
        e.preventDefault();
        var btn = $(this);
        var htm = btn.html();
        var id = btn.attr('data-id');
        if (id) {
            setLoadingBtn(btn);
            $.ajax({
                url: base_url + '/komentar/form',
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
                        $('.mymodal').html(res).modal('show');
                        $('#myModalLabel').html('Form Show Komentar');
                        $('#nama').attr('disabled', true);
                        $('#post').attr('disabled', true);
                        $('#komen').attr('disabled', true);
                        $('.status').attr('disabled', true);
                        $('.btn-submit').remove();
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
    $('#table-komentar').on('click', 'tbody tr td .btn-delete', function(e) {
        e.preventDefault();
        var btn = $(this);
        var htm = btn.html();
        var id = btn.attr('data-id');
        if (id) {
            if (confirm('Yakin hapus komentar ?')) {
                setLoadingBtn(btn);
                $.ajax({
                    url: base_url + '/komentar/delete',
                    type: 'post',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res.status) {
                            successMsg(res.msg);
                            $('#table-komentar').DataTable().ajax.reload();
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
    var oTable = $('#table-komentar').dataTable({
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
                data: 'nama',
                name: 'nama'
            },
            {
                'data': null,
                'searchable': false,
                render: function(data, type, row) {
                    return data.status == 1 ? '<span class=\'label label-danger\'>Not Approve</label>' : '<span class=\'label label-success\'>Approve</label>';
                }
            },
            {
                data: 'komentar',
                name: 'komentar'
            },
            {
                data: 'tgl_simpan',
                name: 'tgl_simpan'
            },
            {
                data: 'tgl_update',
                name: 'tgl_update'
            },
            {
                'data': null,
                'render': function(data, type, row) {
                    return ' <button data-id=\'' + data.id + '\' type=\'button\' class=\'btn btn-sm btn-warning btn-flat btn-show\' title=\'klik untuk show komentar\'><i class=\'fa fa-eye\'></i></button>' +
                        ' <button data-id=\'' + data.id + '\' type=\'button\' class=\'btn btn-sm btn-success btn-flat btn-edit\' title=\'klik untuk edit komentar\'><i class=\'fa fa-edit\'></i></button>' +
                        ' <button data-id=\'' + data.id + '\' type=\'button\' class=\'btn btn-sm btn-danger btn-flat btn-delete\' title=\'klik untuk hapus komentar\'><i class=\'fa fa-trash\'></i></button>';
                }
            },
        ],
        'ajax': function(data, callback, setting) {
            $.ajax({
                url: base_url + '/komentar/list',
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
</script>
<?= $this->endsection() ?>

<?= $this->section('plugin_css') ?>
<?php datatableCss() ?>
<?= $this->endsection() ?>

<?= $this->section('plugin_js') ?>
<?php datatableJs() ?>
<?= $this->endsection() ?>