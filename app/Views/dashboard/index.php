<?= $this->extend('dashboard/layout/back_main') ?>

<?= $this->section('header') ?>
<section class="content-header">
    <h1>
        Dashboard
    </h1>
</section>
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Dashboard - 
<?= $this->endSection() ?>

<?= $this->section('content') ?>
www <?php echo csrf_header(); ?>
<?= $this->endSection() ?>