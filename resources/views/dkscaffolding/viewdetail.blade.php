<?php
$layout= $Scaffolding->getFormLayout();
$columns= $Scaffolding->getFormColumns();
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
                ?>
                <div <?php echo Html::attributes($columnLayout['attributes']); ?>>
                    <?php echo $Scaffolding->getFormLabelView($column); ?>
                    <?php echo $Scaffolding->getFormInputView($column); ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>
