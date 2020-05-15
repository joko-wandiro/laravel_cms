<div class="dk-scaffolding">
	<div class="page-header">
		<h1><?php echo $Scaffolding->getTitle(); ?></h1>
    </div>
    @include($Scaffolding->getTemplate().'.viewdetail')
    @include($Scaffolding->getTemplate().'.formdelete', ['cancel'=>trans('dkscaffolding.btn.cancel')])
</div>
