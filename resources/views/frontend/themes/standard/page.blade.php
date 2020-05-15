<?php
$content = shortcode($page['content']);
$meta_title = $page['meta_title'];
$meta_description = nl2br(get_word($page['meta_description'], 40));
$url = request()->getUri();
?>
@extends('frontend.themes.standard.index')

@section('meta')
<meta name="description" content="<?php echo $meta_description; ?>" />
<meta name="author" content="<?php echo $author; ?>" />
<!-- Twitter Card -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@idcoderblog" />
<meta name="twitter:title" content="<?php echo $page['title']; ?>" />
<meta name="twitter:description" content="<?php echo $meta_description; ?>" />
<!-- OpenGraph -->
<meta property="og:title" content="<?php echo $page['title']; ?>" />
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
    "headline": "<?php echo $page['title']; ?>",
    "image": {
    "@type": "ImageObject",
    "url": "<?php echo url('images/logo.png'); ?>",
    "height": 800,
    "width": 800
    },
    "datePublished": "<?php echo $page['published_at']; ?>",
    "dateModified": "<?php echo $page['updated_at']; ?>",
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
            if (Session::has('alert_success_contact')) {
                ?>
                <p class="alert alert-success fade in"><?php echo e(session('alert_success_contact')); ?><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                <?php
            }
            ?>
            <h1 class="title"><?php echo $page['title']; ?></h1>
            <div id="content">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="<?php echo url('js/jquery.blockUI.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/jquery.dkscaffolding.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/themes/standard/site.js'); ?>"></script>
<script>
    jQuery(document).ready(function ($) {
        // Scaffolding
        DKScaffoldingOptions = {
            loaderContent: '<div class="loader"></div>',
            modifyValidationOptions: function (validationOptions) {
                validationOptions.submitHandler = function (form) {
                    // Send AJAX Request
                    myFeedback = new SiteFeedback();
                    myFeedback.selector = $(form);
                    myFeedback.type = "contact";
                    myAjax = new SiteAjax();
                    myAjax.feedback = myFeedback;
                    data = $(form).serialize();
                    myAjax.send(site.url, data, "json", 'POST');
                }
                return validationOptions;
            }
        }
        Scaffolding = $('.dk-scaffolding').DKScaffolding(DKScaffoldingOptions);
//        Scaffolding = $('.dk-scaffolding').DKScaffolding();
    })
</script>
@endpush