@extends('backend.themes.standard.default')

@section('content')
<?php echo $scaffolding; ?>
<a href="#" id="media-btn" class="hide">Media</a>
<!-- Modal - Confirmation Box -->
<div id="media-manager-box" class="modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo trans('main.media_manager'); ?></h4>
            </div>            
            <div class="modal-body modal-body-scroller">
                <div id="media-gallery">
                    <?php
                    $ct = 1;
                    $medias_length = count($medias);
                    foreach ($medias as $record) {
                        if ($ct % 3 == 1) {
                            ?>
                            <div class="xrow">
                                <?php
                            }
                            ?>
                            <div class="com-4">
                                <div class="media" data-id="<?php echo $record['id']; ?>" data-name="<?php echo $record['name']; ?>" data-src="<?php echo image_url($record['image']); ?>">
                                    <img src="<?php echo image_url_medium($record['image']); ?>" class="img-responsive"/>
                                </div>
                            </div>
                            <?php
                            if ($ct % 3 == 0 || $ct == $medias_length) {
                                ?>
                            </div>
                            <?php
                        }
                        $ct++;
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <div class="xrow">
                    <div class="com-12">
                        <button type="button" class="btn btn-action btn-blue btn-block" value="1"><?php echo trans('main.select'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="<?php echo url('css/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet">
<link href="<?php echo url('css/select2.min.css'); ?>" rel="stylesheet">
@endpush

@push('scripts')
<script type="text/javascript" src="<?php echo url('js/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/bootstrap-datetimepicker.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/select2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/mediamanager.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/backend/posts.js'); ?>"></script>
@endpush