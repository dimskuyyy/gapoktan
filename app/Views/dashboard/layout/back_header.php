<?= $this->section('js') ?>
<script>
$('.btn-logout').click(function(e){
    e.preventDefault();
    if(confirm('Yakin logout ?')){
        var btn=$(this);
        var htm=btn.html();
        setLoadingBtn(btn);
        $.ajax({
            url:base_url+'/logout',
            type:'get',
            dataType:'json',
            success:function(res){
                if(res.status){
                    successMsg(res.msg);
                    setTimeout(function(){
                        location.reload();
                    },800)
                }else{
                    errorMsg(res.msg);
                    resetLoadingBtn(btn,htm);
                }
            },
            error:function(xhr,status,error){
                errorMsg(error);
                resetLoadingBtn(btn,htm);
            }
        })
    }
    return false;
});
</script>
<?= $this->endSection() ?>
<header class="main-header">
    <a href="#" class="logo" style="background-color: #13815e;">
        <span class="logo-mini"><b>ADM</b></span>
        <span class="logo-lg"><b>ADMIN</b></span>
    </a>
    <nav class="navbar navbar-static-top" style="background-color: #1d9d74;">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo base_url() ?>/img/avatar.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo AuthUser()->nama; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header" style="background-color: #1d9d74;">
                                <img src="<?php echo base_url() ?>/img/avatar.png" class="img-circle" alt="User Image">
                                <p>
                                    <b><?php echo AuthUser()->nama; ?></b>
                                    <small>Login at : <?php echo AuthUser()->login_at; ?></small>
                                </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="#" class="btn btn-default btn-logout btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>