<?php
$meta_title = $tag['meta_title'];
$meta_description = nl2br(get_word($tag['meta_description'], 40));
?>
@extends('frontend.themes.standard.index')

@section('meta')
<meta name="description" content="<?php echo $meta_description; ?>" />
@endsection

@section('page_title')
<?php echo $meta_title; ?>
@endsection

@section('content')
<div class="row">
	<div class="col-sm-12">
	@include('frontend.themes.standard.posts')
	</div>
</div>
@endsection