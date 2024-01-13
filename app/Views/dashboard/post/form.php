<?php
helper(['form']);
?>
<div class="box box-widget">
    <div class="box-body">
        <div class="pull-left">
            <button class="btn btn-sm btn-flat btn-primary btn-list"><i class="fa fa-list"></i> List Post</button>
        </div><br><br>
        <?php echo form_open('#', ['class' => 'form-post']);
        if (isset($data)) {
            echo form_hidden('id', $data['post_id']);
        }
        ?>
        <div class="modal-body">

            <div class="form-group">
                <label for='judul'>Judul Konten</label><br>
                <input type="text" name="judul" class="form-control" value="<?= isset($data) ? $data['post_judul'] : '' ?>" required>
            </div>

            <div class="row">
                <div class="col-md-8 ">
                    <div class="form-group">
                        <textarea name="konten" class="form-control" id="konten"><?= isset($data) ? $data['post_konten'] : '' ?></textarea>
                    </div>
                </div>
                <div class="col-md-4 ">

                    <div class="form-group">
                        <label for='kategori'>Kategori</label><br>
                        <select name="kategori" class="form-control select-mod select2">
                            <?php foreach ($kategori as $row) : ?>
                                <?php if (isset($data) && $data['post_kategori_id'] == $row['kat_id']) : ?>
                                    <option value="<?= $row['kat_id'] ?>" selected><?= $row['kat_nama'] ?></option>
                                <?php else : ?>
                                    <option value="<?= $row['kat_id'] ?>"><?= $row['kat_nama'] ?></option>
                                <?php endif; ?>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- Buat kondisi dimana user admin atau penulis:  -->
                    <div class="form-group form-checkbox">
                        <label>Jenis Postingan</label><br>
                        <div class="checkbox-wrap">
                            <div>
                                <input id="berita-jenis" type="radio" name="jenis" value="1" <?= isset($data) ? ($data['post_jenis'] == 1 ? 'checked' : '') : 'checked' ?> required>
                                <label for="berita-jenis" class="btn btn-sm btn-flat btn-default"><i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp; Berita</label>
                            </div>
                            <div>
                                <input id="halaman-jenis" type="radio" name="jenis" value="2" <?= isset($data) ? ($data['post_jenis'] == 2 ? 'checked' : '') : '' ?> required>
                                <label for="halaman-jenis" class="btn btn-sm btn-flat btn-default"><i class="fa fa-window-maximize" aria-hidden="true"></i>&nbsp; Halaman</label>
                            </div>
                        </div>
                    </div>
                    <!-- End of condition -->

                    <!-- Gausah di masukkan dulu karena tabel media belum ada bre: -->

                    <!-- Buat kondisi dimana user admin atau penulis:  -->
                    <div class="form-group form-checkbox">
                        <label>Status Komentar</label><br>
                        <div class="checkbox-wrap">
                            <div>
                                <input id="pending-check" type="radio" name="komentar" value="1" <?= isset($data) ? ($data['post_approve'] == 1 ? 'checked' : '') : 'checked' ?> required>
                                <label for="pending-check" class="btn btn-sm btn-flat btn-default"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp; Pending</label>
                            </div>
                            <div>
                                <?php if (AuthUser()->level == 1) : ?>
                                    <input id="approve-check" type="radio" name="komentar" value="2" <?= isset($data) ? ($data['post_approve'] == 2 ? 'checked' : '') : '' ?> required>
                                    <label for="approve-check" class="btn btn-sm btn-flat btn-default"><i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp; Approve</label>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-checkbox">
                        <label>Status Post</label><br>
                        <div class="checkbox-wrap">
                            <div>
                                <input id="draft-check" type="radio" name="status" value="1" <?= isset($data) ? ($data['post_status'] == 1 ? 'checked' : '') : 'checked' ?> required>
                                <label for="draft-check" class="btn btn-sm btn-flat btn-default"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp; Save as draft</label>
                            </div>
                            <div>
                                <?php if (AuthUser()->level == 1) : ?>
                                    <input id="publish-check" type="radio" name="status" value="2" <?= isset($data) ? ($data['post_status'] == 2 ? 'checked' : '') : '' ?> required>
                                    <label for="publish-check" class="btn btn-sm btn-flat btn-default"><i class="fa fa-cloud" aria-hidden="true"></i>&nbsp; Publish</label>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- End of condition -->

                    <div class="form-group">
                        <label for='media'>Media</label><br>
                        <div class="choose-media">
                            <a href="#" class="btn btn-sm btn-flat btn-primary btn-media" data-backdrop="static"><i class="fa fa-plus"></i> Pilih Media</a>
                            <button type="button" class="btn btn-default btn-flat btn-sm btn-reset-media"><i class="fa fa-recycle" aria-hidden="true"></i></button>
                        </div>
                        <input type="hidden" name="media" id="media-id" value="<?= isset($data) ? $data['post_media_id'] : '' ?>">
                        <div class="source-media">
                            <img id="media-source" <?= isset($media) ? 'src="' . base_url() . 'media/' . $media[0]['media_slug'] . '"' : 'class="no-source"' ?>>
                            <p class="note-media <?= isset($media) ? 'active' : '' ?>">Tidak Ada Cover</p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-close-form" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
                <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-save"></i> Simpan</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>js/wbpanel.js"></script>
<script>
    let placeholder = "<?=base_url('img/front/placeholder/180x180.png')?>";
</script>
<script>
    $(document).ready(function() {
        $('.btn-reset-media').on('click', function(e) {
            let mediaId = $('#media-id').val();
            let mediaSrc = $('#media-source');
            let mediaNote = $('.note-media');
            if (mediaId.length != 0) {
                $('#media-id').val('');
            }
            mediaSrc.addClass('no-source');
            mediaSrc.removeAttr('src');
            mediaNote.removeClass('active');
        });

        $('.form-post').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('.btn-submit');
            var htm = btn.html();
            setLoadingBtn(btn);
            $.ajax({
                url: base_url + '/post/save',
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: function(res) {
                    if (res.status) {
                        successMsg(res.msg);
                        form[0].reset();
                        loadData();
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

        // Select 2
        $('.btn-list,.btn-close-form').click(function(e) {
            e.preventDefault();
            loadData();
        });

        $('.select-mod').select2({
            language: 'id'
        });

        // Memanggil fungsi
        initTinyMce();

        $('.btn-media').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var htm = btn.html();
            setLoadingBtn(btn);
            $.ajax({
                url: base_url + '/post/media',
                data: {
                    key: 'cover'
                },
                type: 'post',
                success: function(res) {
                    resetLoadingBtn(btn, htm);
                    $('.mymodal').html(res).modal('show');
                    setLoadingBtn($('.mymodal #media-list'));
                    viewList(1, null);
                },
                error: function(xhr, status, error) {
                    errorMsg(error);
                    resetLoadingBtn(btn, htm);
                },
            });
        });

        function initTinyMce() {
            let set = 0;
            tinymce.remove();
            tinymce.init({
                selector: 'textarea#konten',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion ',
                placeholder: 'Ketik di sini',
                menubar: 'file edit view insert format tools table help',
                toolbar: "undo redo | code template | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | fullscreen preview | save print | pagebreak anchor codesample | accordion accordionremove | ltr rtl",
                toolbar_sticky: false,
                draggable_modal: true,
                paste_block_drop: true,
                toolbar_sticky_offset: isSmallScreen ? 102 : 108,
                autosave_ask_before_unload: true,
                autosave_interval: '20s',
                autosave_prefix: '{path}{query}-{id}-',
                autosave_restore_when_empty: false,
                autosave_retention: '30m',
                image_advtab: true,
                quickbars_insert_toolbar: 'image quicktable ',
                quickbars_selection_toolbar: 'bold italic underline | blockquote image quicktable quicklink ',
                quickbars_image_toolbar: 'alignleft aligncenter alignright',
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave();
                    });
                    editor.on("Click", function(ed, e) {
                        if ($(ed.target).data("toggle") == "tab") {
                            $(ed.target).tab("show");
                            let tabId = $(ed.target).attr("href");
                            tinymce.activeEditor.dom.removeClass(tinymce.activeEditor.dom.select('.tab-content div'), 'in active')
                            tinymce.activeEditor.dom.addClass(tinymce.activeEditor.dom.select(tabId), 'in active')
                        }
                    });
                },
                link_list: [{
                        title: 'My page 1',
                        value: '<?= base_url('wbpanel/post') ?>'
                    },
                    {
                        title: 'My page 2',
                        value: 'http://www.moxiecode.com'
                    }
                ],
                image_class_list: [{
                        title: 'None',
                        value: ''
                    },
                    {
                        title: 'Some class',
                        value: 'class-name'
                    }
                ],
                importcss_append: false,
                file_picker_types: 'image',
                file_picker_callback: (callback, value, meta) => {
                    if (meta.filetype === 'image') {
                        let stage = set;
                        $.ajax({
                            url: base_url + '/post/media',
                            data: {
                                key: 'tinymce'
                            },
                            type: 'post',
                            success: function(res) {
                                $('.mymodal').html(res).modal('show');
                                setLoadingBtn($('.mymodal #media-list'));
                                viewList(1, null);
                            },
                            error: function(xhr, status, error) {
                                errorMsg(error);
                            }
                        });

                        $(document).on('click', '.modal-content .modal-body .tab-content #media-list .row .media', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            if (set == stage) {
                                let btn = $(this).find('#insert-media');
                                let htm = btn.html();
                                let id = $(this).data('id');
                                let call = $('.mymodal .modal-dialog').data('call');
                                // console.log(call,id)                                                                      
                                if (call == 'tinymce') {
                                    callMedia(call, id, btn, htm, callback);
                                    // return false;
                                }
                            } else {
                                return false;
                            }
                            set += 1;
                        });

                        $('.mymodal').on('submit', '.form-media', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            if (stage == set) {
                                let form = $('.mymodal .form-media');
                                let formData = new FormData($('.mymodal .form-media')[0]);
                                let btn = form.find('.btn-submit');
                                let htm = btn.html();
                                let call = $('.mymodal .modal-dialog').data('call');
                                setLoadingBtn(btn);
                                if (call == 'tinymce') {
                                    submitMedia(form, formData, btn, htm, call, callback);
                                }
                            } else {
                                return false;
                            }
                            set += 1;
                        });
                    }
                },
                templates: [{
                        title: 'Header',
                        description: 'New Header & Wrapper',
                        content: ` <section class="widget">
                        <header class="clearfix"><h4>Paragraphs</h4></header>
                        
                        <!--Content-->
                        <p>Type Here (escape)</p>
                        </section>
                        <p></p>`
                    },
                    {
                        title: 'Blockquote',
                        description: 'New BlockQuote',
                        content: `
                        <blockquote>
                        Pellentesque eleifend semper rhoncus. Aliquam nunc mauris, imperdiet gravida malesuada sit, semper 
                        non erat. Integer dictum laoreet purus, at pretium felis pharetra sit.
                        </blockquote>
                        <p></p>`
                    },
                    {
                        title: 'Tabs',
                        description: 'Tabs Component',
                        content: `
                        <section class="widget">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#item_1" data-toggle="tab" style="cursor: pointer!important;">Item 1</a></li>
                                <li><a href="#item_2" data-toggle="tab" style="cursor: pointer!important;">Item 2</a></li>
                                <li><a href="#item_3" data-toggle="tab" style="cursor: pointer!important;">Item 3</a></li>
                                <li><a href="#item_4" data-toggle="tab" style="cursor: pointer!important;">Item 4</a></li>
                            </ul>
                            
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="item_1">
                                    Quisque eros quam, ultricies et condimentum a, porta quis neque. Mauris ac nisl nunc.
                                    Vivamus fermentum, mi nec convallis congue, turpis dui accumsan velit.
                                    Sed egestas velit at nunc faucibus, ac porttitor diam vestibulum. Sed ultrices purus 
                                    sodales mi lobortis hendrerit.
                                </div>
                                <div class="tab-pane fade" id="item_2">
                                Vel tincidunt dui leo et dui. Vestibulum sit  eros non risus suscipit faucibus. 
                                    Sed quis tristique dui. Nullam dignissim sit   quis fringilla. In eget urna quam.
                                </div>
                                <div class="tab-pane fade" id="item_3">
                                    Proin bibendum, libero in dictum pellentesque,  enim varius tellus, nec aliquet  risus non sem. 
                                    In pulvinar pharetra , ut hendrerit nisi laoreet et. Pellentesque habitant morbi tristique 
                                    senectus et netus et malesuada fames ac turpis egestas. 
                                </div>
                                <div class="tab-pane fade" id="item_4">
                                Curabitur placerat, quam vel bibendum pretium, arcu dui consectetur tellus, 
                                    et tincidunt turpis turpis et quam.
                                </div>
                            </div>
                        </section>
                        <p></p>`
                    },
                    {
                        title: 'New Table',
                        description: 'creates a new table',
                        content: `
                            <table class="table table-hover table-striped table-bordered">                                
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <p></p>
                        `
                    },
                    {
                        title: "Accordion",
                        description: "Creates new Accordion",
                        content: `
                            <section class="widget">
                                <div class="accordion">
                                    <div class="header"><h5>Item 1</h5><i class="fa fa-plus"></i></div>
                                    <div class="content">
                                        <p>
                                            Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
                                            ut neque. 
                                        </p>
                                    </div>

                                    <div class="header"><h5>Item 2</h5><i class="fa fa-plus"></i></div>
                                    <div class="content">
                                        <p>
                                            Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
                                            purus. 
                                        </p>
                                    </div>

                                    <div class="header"><h5>Item 3</h5><i class="fa fa-plus"></i></div>
                                    <div class="content">
                                        <p>
                                            Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
                                            Phasellus pellentesque purus in massa. Aenean in pede. 
                                        </p>
                                    </div>

                                    <div class="header"><h5>Item 4</h5><i class="fa fa-plus"></i></div>
                                    <div class="content">
                                        <p>
                                            Cras dictum. Pellentesque habitant morbi tristique senectus et netus
                                            et malesuada fames ac turpis egestas.
                                        </p>
                                    </div>
                                </div>
                            </section>
                            <p></p>
                            `
                    },
                    {
                        title: "Card Wrapper",
                        description: "Creates new Card wrapper with 1 example",
                        content: `
                            <section class="widget">
                                <div class="row">
                                    <div class="col-xs-4 card-feature">
                                        <div class="card-wrapper">
                                            <div class="image-card">
                                                <img src="${placeholder}" alt="">
                                            </div>
                                            <div class="card-information">
                                                <h4>Nama</h4>
                                                <p>Detail</p>
                                            </div>
                                            <div class="card-social-media">
                                                <a href="#"><span class="sc-sm sc-instagram"></span></a>
                                                <a href="#"><span class="sc-sm sc-facebook"></span></a>
                                                <a href="#"><span class="sc-sm sc-youtube"></span></a>
                                                <a href="#"><span class="sc-sm sc-twitter"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <p>Add_Here</p>
                                </div>
                            </section>
                            <p></p>
                            `
                    },
                    {
                        title: "Card Item",
                        description: "Creates new Card Item inside wrapper",
                        content: `
                            <div class="col-xs-4 card-feature">
                                <div class="card-wrapper">
                                    <div class="image-card">
                                        <img src="${placeholder}" alt="">
                                    </div>
                                    <div class="card-information">
                                        <h4>Nama</h4>
                                        <p>Detail</p>
                                    </div>
                                    <div class="card-social-media">
                                        <a href="#"><span class="sc-sm sc-instagram"></span></a>
                                        <a href="#"><span class="sc-sm sc-facebook"></span></a>
                                        <a href="#"><span class="sc-sm sc-youtube"></span></a>
                                        <a href="#"><span class="sc-sm sc-twitter"></span></a>
                                    </div>
                                </div>
                            </div>
                            <p>Add_Here</p>
                            `
                    },
                ],
                mobile: {
                    menubar: true,
                    toolbar_mode: 'sliding'
                },
                template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
                template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
                height: 650,
                image_caption: true,
                noneditable_class: 'mceNonEditable',
                toolbar_mode: 'sliding',
                contextmenu: 'link image table',
                skin: useDarkMode ? 'oxide-dark' : 'oxide',
                content_css: [
                    root_url + 'css/front/font-awesome.min.css',
                    root_url + 'css/front/style.min.css',
                    root_url + 'css/front/custom.css'
                ],
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px; margin:8px!important; }',
                promotion: false,
                valid_elements: '*[*]',
                extended_valid_elements: 'script[src|async|defer|type|charset]'
            });
        }
    });
</script>
<script>
    $('[name="jenis"]').on('change', function() {
        if (this.value != 1) {
            $('[name="kategori"]').attr('disabled', 'disabled');
        } else {
            $('[name="kategori"]').removeAttr('disabled');
        }
    });
</script>