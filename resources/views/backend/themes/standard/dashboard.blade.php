@extends('backend.themes.standard.default')

@section('content')
<div class="page-header">
    <h1><?php echo trans('main.dashboard'); ?></h1>
</div>
<div class="dashboard-information">
	<div class="row row-board-info">
		<div class="col-sm-3">
			<div class="card-block">
				<h2><?php echo $postTotal; ?></h2>
				<h6 class="text-muted"><?php echo trans('main.posts'); ?></h6>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="card-block">
				<h2><?php echo $categoryTotal; ?></h2>
				<h6 class="text-muted"><?php echo trans('main.categories'); ?></h6>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="card-block">
				<h2><?php echo $tagTotal; ?></h2>
				<h6 class="text-muted"><?php echo trans('main.tags'); ?></h6>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="card-block">
				<h2><?php echo $commentTotal; ?></h2>
				<h6 class="text-muted"><?php echo trans('main.comments'); ?></h6>
			</div>
		</div>
	</div>
	<div class="row row-board-info">
		<div class="col-sm-3">
			<div class="card-block">
				<h2><?php echo $pendingCommentTotal; ?></h2>
				<h6 class="text-muted"><?php echo trans('main.pending.comments'); ?></h6>
			</div>
		</div>
	</div>
</div>
@endsection