<?php $request = service('request'); ?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo base_url() ?>/img/avatar.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= AuthUser()->nama; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= $request->uri->getSegment(2) === "" ? 'active' : ''; ?>">
                <a href="<?php echo base_url('admin'); ?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="<?= $request->uri->getSegment(2) === "media" ? 'active' : ''; ?>">
                <a href="<?php echo base_url('admin/media'); ?>">
                    <i class="fa fa-folder-open"></i> <span>Media</span>
                </a>
            </li>
            <li class="<?= $request->uri->getSegment(2) === "post" ? 'active' : ''; ?>">
                <a href="<?php echo base_url('admin/post'); ?>">
                    <i class="fa fa-newspaper-o"></i> <span>Post</span>
                </a>
            </li>
            <?php if (AuthUser()->level == 1) : ?>
                <li class="<?= $request->uri->getSegment(2) === "kategori" ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('admin/kategori'); ?>">
                        <i class="fa fa-tags"></i> <span>Kategori</span>
                    </a>
                </li>
                <li class="<?= $request->uri->getSegment(2) === "produk" ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('admin/produk'); ?>">
                        <i class="fa fa-archive"></i> <span>Produk</span>
                    </a>
                </li>
                <li class="<?= $request->uri->getSegment(2) === "komentar" ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('admin/komentar'); ?>">
                        <i class="fa fa-comments"></i> <span>Komentar</span>
                    </a>
                </li>
                <li class="<?= $request->uri->getSegment(2) === "running-text" ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('admin/running-text'); ?>">
                        <i class="fa fa-bullhorn"></i> <span>Running Text</span>
                    </a>
                </li>
                <li class="<?= $request->uri->getSegment(2) === "user" ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('admin/user'); ?>">
                        <i class="fa fa-user"></i> <span>User</span>
                    </a>
                </li>
                <li class="treeview <?= $request->uri->getSegment(2) === "setting" ? 'active' : ''; ?>">
                    <a href="#">
                        <i class="fa fa-gear"></i>
                        <span>Setting</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "sosial_media") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/sosial_media'); ?>"><i class="fa fa-globe"></i> Sosial Media</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "judul") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/judul'); ?>"><i class="fa fa-text-height"></i> Judul Website</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "banner") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/banner'); ?>"><i class="fa fa-picture-o"></i> Banner</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "logo") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/logo'); ?>"><i class="fa fa-circle-o"></i> Logo</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "status_mahasiswa") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/status_mahasiswa'); ?>"><i class="fa fa-users"></i> Status Mahasiswa</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "kalender_akademik") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/kalender_akademik'); ?>"><i class="fa fa-calendar"></i> Kalender Akademik</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "embedded_youtube") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/embedded_youtube'); ?>"><i class="fa fa-youtube-play"></i> Embedded Youtube</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "footer_kontak") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/footer_kontak'); ?>"><i class="fa fa-address-book-o"></i> Footer Kontak</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "footer_link") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/footer_link'); ?>"><i class="fa fa-link"></i> Footer Link</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "footer_image") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/footer_image'); ?>"><i class="fa fa-file-image-o"></i> Footer Image</a></li>

                        <li class="<?= ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3) === "footer_alamat") ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/setting/footer_alamat'); ?>"><i class="fa fa-map-marker"></i> Footer Alamat</a></li>
                    </ul>
                </li>
            <?php endif; ?>

        </ul>
    </section>
</aside>