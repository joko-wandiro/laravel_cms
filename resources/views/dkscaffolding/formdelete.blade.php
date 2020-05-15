<?php
$Model= $Scaffolding->getModel();
?>
{!! Form::model($Model, ['method'=>'DELETE', 'url'=>$Scaffolding->getFormAction(),'enctype'=>'multipart/form-data']) !!}
<?php
$identifier= $Scaffolding->getIdentifier();
echo Form::hidden('identifier', $identifier);
echo $Scaffolding->getFormInputIndexes();
?>
<div class="row">
    <div class="form-group">
        <div class="col-sm-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
            <?php echo trans('dkscaffolding.entry.delete'); ?>
            </div>
            <div class="dk-container-form-submit">
                <button type="submit" class="btn btn-primary"><?php echo trans('dkscaffolding.btn.submit.delete'); ?></button>
                <a href="<?php echo $Scaffolding->getFormAction(); ?>" class="btn btn-default"><?php echo $cancel; ?></a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
