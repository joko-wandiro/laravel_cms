@extends('frontend.themes.standard.index')

@section('meta')
<meta name="description" content="IdCoderBlog berisi artikel-artikel tentang pemrograman dan lainnya yang dibuat oleh penulis." />
<meta name="author" content="<?php echo $author; ?>" />
<!-- Twitter Card -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@idcoderblog" />
<meta name="twitter:title" content="IdCoderBlog" />
<meta name="twitter:description" content="IdCoderBlog berisi artikel-artikel tentang pemrograman dan lainnya yang dibuat oleh penulis." />
<!-- OpenGraph -->
<meta property="og:title" content="IdCoderBlog" />
<meta property="og:type" content="article" />
<meta property="og:url" content="http://www.idcoderblog.co.nf/" />
<meta property="og:description" content="IdCoderBlog berisi artikel-artikel tentang pemrograman dan lainnya yang dibuat oleh penulis." />
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Organization",
  "url": "<?php echo url(''); ?>",
  "logo": "<?php echo url('images/logo.png'); ?>"
}
</script>
@endsection

@section('page_title')
Homepage
@endsection

@section('content')
<div class="row">
	<div class="col-sm-12">
		<?php
		if( count($posts) ){
			$attributes= array(
				'class'=>'form-control',
				'placeholder'=>trans('main.search.placeholder'),
			);
		?>
		{!! Form::open(['id'=>'form-search']) !!}
		{!! Form::text('search', null, $attributes) !!}
		{!! Form::close() !!}
		<?php
		}
		?>
		@include('frontend.themes.standard.posts')
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="<?php echo url('js/themes/standard/search.js'); ?>"></script>
@endpush