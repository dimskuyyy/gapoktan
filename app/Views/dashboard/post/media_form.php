<?php
helper(['form']);
?>
<div class="modal-dialog modal-lg" role="document" data-call="<?= $key ?>">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Form Media</h4>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#media-list-content">List</a></li>
                <li role="presentation"><a href="#form">Input</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="media-list-content">
                    <div class="pull-right" style="margin-top: 1rem;">
                        <div class="input-group">
                            <input type="text" class="form-control" id="keyword" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" disabled>Go!</button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                    <div id="media-list">
                        <!-- Konten Media List akan ditampilkan di sini -->

                    </div>
                </div>
                <div id="form" class="tab-pane" style="margin-top: 2rem;">
                    <?php echo form_open_multipart('#', ['class' => 'form-media']); ?>
                    <div class="form-group">
                        <label for='nama'>Nama Media</label>
                        <input type="text" name="nama" class="form-control" id="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Media</label>
                        <div id="imagePreview">
                            <img id="preview" src="#" alt="Preview" class="img-thumbnail preview hidden">
                        </div>
                        <input type="file" name="path" accept="image/png, image/jpeg, image/jpg, image/webp" id="imageInput">
                    </div>
                    <button type="submit" class="btn btn-primary btn-submit"><i class="fa fa-save"></i> Simpan</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <script>
        </script>
        <script>
            $(document).ready(function() {
                // Ketika tab di klik, tampilkan konten yang sesuai
                $('.nav-tabs a').click(function(event) {
                    event.preventDefault(); // Menghentikan perilaku bawaan dari anchor tag
                    $(this).tab('show'); // Menampilkan tab yang di-klik
                });

            });
        </script>