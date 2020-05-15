@extends('backend.auth.default')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div id="panel-login" class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                	<?php
                	$inputAttributes= array('class'=>'form-control');
					$errorMessages= $errors->getMessages();
					$labelError= '<label class="error">%1$s</label>';
					if (Session::has('login_failure')) {
					?>
					    <p class="alert alert-danger"><?php echo e(session('login_failure')); ?></p>
					<?php
					}
					?>
					{!! Form::open(['url'=>action('BackEnd\AuthController@login'),
					'enctype'=>'multipart/form-data', 'class'=>'form-horizontal']) !!}
                        <div class="form-group">
                        	<label for="email" class="col-sm-3 control-label"><?php echo trans('form.login.email'); ?> <span class="required">*</span></label>
                        	<div class="col-sm-9">
                        		<?php echo Form::text('email', null, $inputAttributes); ?>
	                        	<?php
								// Display errors
								if (isset($errorMessages['email'])) {
								    foreach ($errorMessages['email'] as $errorMessage) {
								        echo sprintf($labelError, e($errorMessage));
								    }
								}
	                        	?>
                        	</div>
                        </div>
                        <div class="form-group">
                        	<label for="email" class="col-sm-3 control-label"><?php echo trans('form.login.password'); ?> <span class="required">*</span></label>
                        	<div class="col-sm-9">
                        		<?php echo Form::password('password', $inputAttributes); ?>
	                        	<?php
								// Display errors
								if (isset($errorMessages['password'])) {
								    foreach ($errorMessages['password'] as $errorMessage) {
								        echo sprintf($labelError, e($errorMessage));
								    }
								}
	                        	?>
                        	</div>
                        </div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<button type="submit" class="btn btn-primary">Login</button>
							</div>
						</div>
					{!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
