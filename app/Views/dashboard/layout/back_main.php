<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $this->renderSection('title') ?> - ADMIN PANEL</title>
    <?= csrf_meta(); ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?=$this->renderSection('front_css')?>
    <link rel="stylesheet" href="<?php echo base_url() ?>plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>plugins/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>plugins/toastr/toastr.min.css">
    <?= $this->renderSection('plugin_css') ?>
    <link rel="stylesheet" href="<?php echo base_url() ?>css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>css/component/style.css">
    <?= $this->renderSection('css') ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?= $this->include('dashboard/layout/back_header') ?>
        <?= $this->include('dashboard/layout/back_menubar') ?>
        <div class="content-wrapper">
            <?= $this->renderSection('header') ?>
            <section class="content">
                <?= $this->renderSection('content') ?>
            </section>
        </div>
        <footer class="main-footer">
            <strong>KOSA BANGSA</strong>
        </footer>
    </div>
    <div class="modal fade mymodal" tabindex="-1" role="dialog" data-backdrop="static"></div>
    <script src="<?php echo base_url() ?>js/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>plugins/toastr/toastr.min.js"></script>
    <script src="<?php echo base_url() ?>js/adminlte.min.js"></script>
    <?= $this->renderSection('plugin_js') ?>
    <script src="<?php echo base_url() ?>js/base.js"></script>
    <script src="<?php echo base_url() ?>js/wbpanel.js"></script>
    <script>
        var base_url = '<?php echo base_url(); ?>admin';
        var root_url = '<?php echo base_url(); ?>';
    </script>
    <?= $this->renderSection('js') ?>
</body>

</html>