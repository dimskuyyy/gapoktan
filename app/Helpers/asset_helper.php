<?php
if(!function_exists('datatableCss')) {
    function datatableCss()
    {
        ?><link rel="stylesheet" href="<?php echo base_url() ?>plugins/datatable/datatables.min.css"><?php
    }
}
if(!function_exists('datatableJs')) {
    function datatableJs()
    {
        ?><script src="<?php echo base_url() ?>plugins/datatable/datatables.min.js"></script><?php
    }
}
if(!function_exists('nestableJs')) {
    function nestableJs()
    {
        ?><script src="<?php echo base_url() ?>plugins/nestable/jquery.nestable.min.js"></script><?php
    }
}
if(!function_exists('nestableCss')) {
    function nestableCss()
    {
        ?><link rel="stylesheet" href="<?php echo base_url() ?>plugins/nestable/jquery.nestable.min.css"><?php
    }
}
if(!function_exists('select2Css')) {
    function select2Css()
    {
        ?><link rel="stylesheet" href="<?php echo base_url() ?>plugins/select2/select2.min.css"><?php
    }
}
if(!function_exists('select2Js')) {
    function select2Js()
    {
        ?><script src="<?php echo base_url() ?>plugins/select2/select2.min.js"></script>
        <script src="<?php echo base_url() ?>plugins/select2/lang/id.js"></script><?php
    }
}
if(!function_exists('ckeditorJs')) {
    function ckeditorJs()
    {
        ?><script src="<?php echo base_url() ?>plugins/ckeditor/ckeditor.js"></script><?php
    }
}
if(!function_exists('tinymceJS')) {
    function tinymceJS()
    {
        ?><script src="<?php echo base_url() ?>plugins/tinymce/tinymce.min.js"></script><?php
    }
}