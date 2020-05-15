<?php
$identifier= $Scaffolding->getIdentifier();
if (Session::has('dk_'.$identifier.'_info_success') ||
Session::has('dk_'.$identifier.'_info_error') || Session::has('dk_'.$identifier.'_info_info') ) {
?>
    <?php
    if (Session::has('dk_'.$identifier.'_info_success')) {
    ?>
        <p class="alert alert-success fade in"><?php echo e(session('dk_'.$identifier.'_info_success')); ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
    <?php
    }
    if (Session::has('dk_'.$identifier.'_info_error')) {
    ?>
        <p class="alert alert-danger fade in"><?php echo e(session('dk_'.$identifier.'_info_error')); ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
    <?php
    }
    if (Session::has('dk_'.$identifier.'_info_info')) {
    ?>
        <p class="alert alert-info fade in"><?php echo e(session('dk_'.$identifier.'_info_info')); ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
    <?php
    }
    ?>
<?php
}
?>
