<?php
$meta_title = $post['meta_title'];
$meta_description = nl2br(get_word($post['meta_description'], 40));
$url = request()->getUri();
?>
@extends('frontend.themes.standard.index')

@section('meta')
<meta name="description" content="<?php echo $meta_description; ?>" />
<meta name="author" content="<?php echo $author; ?>" />
<!-- Twitter Card -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@idcoderblog" />
<meta name="twitter:title" content="<?php echo $post['title']; ?>" />
<meta name="twitter:description" content="<?php echo $meta_description; ?>" />
<!-- OpenGraph -->
<meta property="og:title" content="<?php echo $post['title']; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo $url; ?>" />
<meta property="og:description" content="<?php echo $meta_description; ?>" />
<script type="application/ld+json">
    {
    "@context": "http://schema.org",
    "@type": "Article",
    "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo url(''); ?>"
    },
    "headline": "<?php echo $post['title']; ?>",
    "image": {
    "@type": "ImageObject",
    "url": "<?php echo url('images/logo.png'); ?>",
    "height": 800,
    "width": 800
    },
    "datePublished": "<?php echo $post['published_at']; ?>",
    "dateModified": "<?php echo $post['updated_at']; ?>",
    "author": {
    "@type": "Person",
    "name": "<?php echo $author; ?>"
    },
    "publisher": {
    "@type": "Organization",
    "name": "idcoderblog",
    "logo": {
    "@type": "ImageObject",
    "url": "<?php echo url('images/logo.png'); ?>",
    "width": 40,
    "height": 28
    }
    },
    "description": "<?php echo $meta_description; ?>"
    }
</script>
@endsection

@section('page_title')
<?php echo $meta_title; ?>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div id="single-post">
            <?php
            if (Session::has('alert_success_comment')) {
                ?>
                <p class="alert alert-success fade in"><?php echo e(session('alert_success_comment')); ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                <?php
            }
            ?>
            <?php
            $categoryUrl = category_url($post['category']);
            $categoryLink = '<a href="' . $categoryUrl . '">' . $post['category'] . '</a>';
            ?>
            <h1 class="title"><?php echo $post['title']; ?></h1>
            <p class="description"><?php echo sprintf(trans('main.post.description'), $categoryLink, date('D jS F Y g:i A', strtotime($post['published_at']))); ?></p>
            <div id="content">
                <?php echo $post['content']; ?>
                <div class="tags">
                    <?php
                    $tags = explode(",", $post['tags']);
                    foreach ($tags as $tag) {
                        ?>
                        <a href="<?php echo tag_url($tag); ?>"><?php echo $tag; ?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            @include('frontend.themes.standard.comments')
            <div id="comment-form">
                <?php echo $commentForm; ?>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="<?php echo url('js/jquery.blockUI.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/jquery.dkscaffolding.js'); ?>"></script>
<script>
jQuery(document).ready(function ($) {
    Scaffolding = $('.dk-scaffolding').DKScaffolding();
})
</script>
@endpush