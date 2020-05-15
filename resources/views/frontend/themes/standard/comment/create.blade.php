<?php
$Model= $Scaffolding->getModel();
?>
<div class="dk-scaffolding" data-action="form">
    <div class="page-header">
        <h2><?php echo trans('main.comment.form.title'); ?></h2>
    </div>
    {!! Form::model($Model, ['url'=>$Scaffolding->getFormAction(),'enctype'=>'multipart/form-data']) !!}
    @include($Scaffolding->getTemplate().'.form', ['submit'=>trans('dkscaffolding.btn.submit.create'), 'cancel'=>trans('dkscaffolding.btn.cancel')])
    {!! Form::close() !!}
</div>
