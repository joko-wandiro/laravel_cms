<?php
$identifier= $Scaffolding->getIdentifier();
$layout= $Scaffolding->getFormLayout();
$columns= $Scaffolding->getFormColumns();
$errorMessages    = $errors->getMessages();
echo Form::hidden('identifier', $identifier);
echo $Scaffolding->getFormInputIndexes();
?>
<div class="dk-section-form-elements">
    <?php
    foreach ($layout as $rowId => $row) {
        ?>
        <div class="row dk-section-<?php echo $rowId+1; ?>">
            <?php
            foreach ($row as $columnLayout) {
                $columnId= $columnLayout['name'];
                $column     = $columns[$columnId];
                $inputType= $column['type'];
                ?>
                <div <?php echo Html::attributes($columnLayout['attributes']); ?>>
                <?php
                if ($Scaffolding->hasFormGroup($column)) {
                    echo $Scaffolding->getFormGroup($column);
                } else {
                    switch ($inputType) {
                        case "checkbox":
                        ?>
                        <div class="checkbox">
                            <label>
                                <?php echo $Scaffolding->getFormInput($column); ?> <?php echo $Scaffolding->getFormLabelCheckbox($column); ?>
                            </label>
                        </div>
                        <?php echo $Scaffolding->getFormInputError($columnId); ?>
                        <?php
                            break;
                        default:
                        ?>
                        <div class="form-group">
                            <?php echo $Scaffolding->getFormLabel($column); ?>
                            <?php echo $Scaffolding->getFormInput($column); ?>
                            <?php echo $Scaffolding->getFormInputError($columnId); ?>
                        </div>
                        <?php
                    }
                }
                ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-12">
            <button type="submit" id="btn-send" class="btn"><?php echo trans('main.send'); ?></button>
        </div>
    </div>
</div>
<script>
window['validationRules']= <?php echo json_encode($Scaffolding->getValidationRulesJs()); ?>
</script>
