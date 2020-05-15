<div class="dk-scaffolding">
	<div class="page-header">
		<h1><?php echo $Scaffolding->getTitle(); ?></h1>
    </div>
    @include($Scaffolding->getTemplate().'.viewdetail')
    <div class="form-group">
        <a href="<?php echo $Scaffolding->getFormAction(); ?>" class="btn btn-default"><?php echo trans('dkscaffolding.btn.back'); ?></a>
    </div>
</div>
