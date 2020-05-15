<?php
$inputAttributes= array("class"=>"form-control");
$bulkActions= array(
	""=>trans('main.select.bulk.default'),
	"approve"=>"Approve",
	"unapprove"=>"Unapprove",
	"delete"=>"Delete",
);
?>
<div class="dk-section-bulk-action">
	<?php echo Form::select('xbulkaction', $bulkActions, null, $inputAttributes); ?>
	<button type="button" id="btn-apply-bulk-action" class="btn btn-primary"><?php echo trans('main.apply'); ?></button>
</div>