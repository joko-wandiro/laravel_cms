<?php
$Model= $Scaffolding->getModel();
?>
<div class="dk-scaffolding" data-action="form">
    <div class="page-header">
        <h1><?php echo $Scaffolding->getTitle(); ?></h1>
    </div>
    {!! Form::model($Model, ['method'=>'PUT', 'url'=>$Scaffolding->getFormAction(),'enctype'=>'multipart/form-data']) !!}
    <?php
    echo Form::hidden('idx_old', serialize($Model->getAttributes()));
    ?>
    @include($Scaffolding->getTemplate().'.form', ['submit'=>trans('dkscaffolding.btn.submit.update'), 'cancel'=>trans('dkscaffolding.btn.cancel')])
    {!! Form::close() !!}
</div>
