<?php
$Model= $Scaffolding->getModel();
?>
<div class="dk-scaffolding" data-action="form">
    {!! Form::model($Model, ['url'=>$Scaffolding->getFormAction(),'enctype'=>'multipart/form-data']) !!}
    @include($Scaffolding->getTemplate().'.form', ['submit'=>trans('dkscaffolding.btn.submit.create'), 'cancel'=>trans('dkscaffolding.btn.cancel')])
    {!! Form::close() !!}
</div>
