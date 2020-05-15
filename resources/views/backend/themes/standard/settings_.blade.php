@extends('backend.themes.standard.default')

@push('styles')
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    #sortable1, #sortable2 {
        border: 1px solid #eee;
        width: auto;
        min-height: 40px;
        list-style-type: none;
        margin: 0px 0px 20px 0px;
        padding: 10px;
        margin-right: 10px;
    }
    #sortable1 li, #sortable2 li {
        display: block;
        margin: 0px auto;
        padding: 5px;
        font-size: 14px;
        width: 100%;
        min-height: 32px;
        margin-bottom: 4px;
    }
    #sortable1 li:last-child, #sortable2 li:last-child {
        margin-bottom: 0px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><?php echo trans('main.dashboard'); ?></h1>
</div>
<div class="dashboard-information">
    <div class="row">
        <div class="col-sm-12">
            {{ Form::open(array('url' => $url, 'method'=>'POST', 'class'=>'form-horizontal')) }}
            <div class="form-group">
                <label for="homepage" class="col-sm-2 control-label"><?php echo trans('main.homepage'); ?></label>
                <div class="col-sm-10">
                    <select name="homepage" id="homepage" class="form-control">
                        <option value=""><?php echo trans('main.select.default'); ?></option>
                        <?php
                        foreach ($pages as $page) {
                            ?>
                            <option value="<?php echo $page->id; ?>"><?php echo $page->title; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="submit" class="btn btn-default" value="Submit" />
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo url('js/themes/standard/site.js'); ?>"></script>
<script>
jQuery(document).ready(function ($) {
    $('form').submit(function (e) {
        e.preventDefault();
        $form = $(this);
        url = $form.attr('action');
        // Send AJAX Request
        myFeedback = new SiteFeedback();
        myFeedback.selector = $form;
        myFeedback.type = "settings";
        myAjax = new SiteAjax();
        myAjax.feedback = myFeedback;
        data = $form.serialize();
        myAjax.send(url, data, "json", 'POST');
    })
})
</script>
@endpush