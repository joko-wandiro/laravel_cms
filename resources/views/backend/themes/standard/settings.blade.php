@extends('backend.themes.standard.default')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="dk-scaffolding dk-scaffolding-form">
            <?php
            if (Session::has('dk_settings_info_success')) {
                ?>
                <div id="alert-content">
                    <p class="alert alert-success fade in"><?php echo e(session('dk_settings_info_success')); ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                </div>
                <?php
            }
            $errors = request()->session()->get('errors');
            $errorMessages = array();
            if ($errors) {
                $errorMessages = $errors->getMessages();
            }
            $labelError = '<label class="error">%1$s</label>';
            $url = action(config('app.backend_namespace') . 'SettingsController@update');
            echo Form::open(['method' => 'PUT', 'url' => $url, 'enctype' => 'multipart/form-data']);
            $attributes = array('class' => 'form-control');
            echo Form::hidden('logo_old', settings('logo'), $attributes);
            foreach ($settings as $setting) {
                $type = $setting['type'];
                $name = $setting['name'];
                ?>
                <div class="form-group">
                    <label for="<?php echo $name; ?>"><?php echo trans('dkscaffolding.column.' . $name); ?> <span class="required">*</span></label>
                    <?php
                    switch ($type) {
                        case "select":
                            echo Form::select($name, $pages, $setting['value'], $attributes);
                            break;
                        case "text":
                        case "textarea":
                            echo Form::$type($name, $setting['value'], $attributes);
                            break;
                        case "file":
                            if (settings('logo')) {
                                ?>
                                <p><img src="<?php echo image_url(settings('logo')); ?>"/></p>
                                <?php
                            }
                            echo Form::file($name, $attributes);
                            break;
                    }
                    // Display errors
                    if (isset($errorMessages[$name])) {
                        foreach ($errorMessages[$name] as $errorMessage) {
                            echo sprintf($labelError, e($errorMessage));
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-blue" value="<?php echo trans('dkscaffolding.btn.submit.update'); ?>" />
            </div>            
            <?php
            echo Form::close();
            ?>
        </div>
    </div>
</div>
@endsection