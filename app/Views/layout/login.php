<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $this->renderSection('title') ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url() ?>plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>plugins/fontawesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>css/adminlte.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
</head>
<body class="hold-transition login-page">
    <?= $this->renderSection('content') ?>
    <script src="<?php echo base_url() ?>js/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>plugins/toastr/toastr.min.js"></script>
    <script src="<?php echo base_url() ?>js/base.js"></script>
    <script>var base_url='<?php echo base_url(); ?>';</script>
    <?= $this->renderSection('js') ?>
</body>
</html>
