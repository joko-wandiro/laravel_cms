<?php
$identifier= $Scaffolding->getIdentifier();
$extraAttributes = ' id=dk-scaffolding-'.$identifier;
$visibility= $Scaffolding->getVisibilityListElements();
$Request= $Scaffolding->getRequest();
$attributes= array(
    'class' => 'form-control',
);
?>
<div class="dk-scaffolding"<?php echo $extraAttributes; ?> data-action="list">
    <div class="page-header">
        <h1><?php echo $Scaffolding->getTitle(); ?></h1>
    </div>
    @include($Scaffolding->getTemplate().'.flash', ['Scaffolding'=>$Scaffolding])
    <?php
    if ($visibility['create_button']) {
        ?>
        <div class="dk-btn-create">
            <a href="<?php echo $Scaffolding->getCreateButtonUrl(); ?>" class="btn btn-primary"><?php echo sprintf(trans('dkscaffolding.btn.create.text')); ?></a>
        </div>
        <?php
    }
    ?>
    {!! Form::open(['method'=>'POST', 'url'=>$Scaffolding->getFormAction(), 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal']) !!}
    {!! Form::hidden('identifier', $identifier, array()) !!}
    {!! Form::hidden('filetype', null, array()) !!}
    <?php
    // Hook Action listFormStart
    $Scaffolding->doHooks("listFormStart", array($Scaffolding));
    if ($visibility['multi_search'] || $visibility['submit_button']) {
    ?>
    <div class="row dk-section-multiple-search">
        <div class="col-sm-10">
        <?php
        if ($visibility['multi_search']) {
            echo Form::text('search[multiple]', $Request['search']['multiple'], $attributes);
        }
        ?>
        </div>
        <div class="col-sm-2">
            <?php
            if ($visibility['submit_button']) {
                ?>
                <div class="dk-section-form-buttons">
                <?php echo Form::submit("Submit", ['class'=>'btn btn-primary']); ?>
                <?php echo Form::reset("Reset", ['class'=>'btn btn-default']); ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    }
    // Hook Action listBeforeTable
    $Scaffolding->doHooks("listBeforeTable", array($Scaffolding));
    $columns= $Scaffolding->getListColumns();
    $searchColumns= $Scaffolding->getListSearchColumns();
    $records= $Scaffolding->getListRecords();
    $columnsOrder= $Scaffolding->getColumnsOrder();
    $columnProperties= $Scaffolding->getColumnProperties();
    ?>
    <div class="table-responsive">
        <table class="table table-hover table-condensed">
            <thead>
                <tr class="dk-table-row-order">
                <?php
                foreach ($columns as $columnIndex => $column) {
                    $widthAttribute= isset($columnProperties[$columnIndex]['width']) ?
                    ' width="'.$columnProperties[$columnIndex]['width'] . '"' : '';
                    if (array_key_exists($columnIndex, $columnsOrder)) {
                        $columnOrder= $columnsOrder[$columnIndex];
                        $orderTypeClass = ( $columnOrder['orderType'] == "ASC" ) ? "headerSortUp" : "headerSortDown";
                        $status         = ( $columnOrder['active'] ) ? " active" : "";
                        ?>
                        <th<?php echo $widthAttribute; ?> class="<?php echo $orderTypeClass.$status; ?>">
                            <a href="<?php echo $columnOrder['url']; ?>" class="sortable">
                                <?php echo $column; ?>
                            </a>
                        </th>
                        <?php
                    } else {
                        ?>
                        <th<?php echo $widthAttribute; ?>><div><?php echo $column; ?></div></th>
                        <?php
                    }
                }
                ?>
                </tr>
                <?php
                if ($visibility['single_search']) {
                ?>
                <tr class="dk-table-row-search">
                    <?php
                    foreach ($columns as $columnIndex => $column) {
                    ?>
                        <th><?php echo $searchColumns[$columnIndex]; ?></th>
                    <?php
                    }
                    ?>
                </tr>
                <?php
                }
                ?>
            </thead>
            <tbody>
                <?php
                if (count($records)) {
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <?php
                            foreach ($columns as $columnIndex => $column) {
                            ?>
                            <td>
                                <?php echo $record[$columnIndex]; ?>
                            </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="<?php echo count($columns); ?>"><?php echo trans('dkscaffolding.no.item'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-2">
        <?php
        if ($visibility['records_per_page']) {
        ?>
        <div class="dk-entries-per-page"><?php echo Form::select('recordsPerPage', $Scaffolding->getListOfRecordsPerPage(), $Request['recordsPerPage'], $attributes); ?></div>
            <?php
        }
        ?>
        </div>
        <div class="col-sm-10">
            <?php
            if ($visibility['pagination'] && $visibility['pagination_info']) {
            ?>
            <div class="row dk-section-pagination">
                <div class="col-sm-6">
                <?php
                if ($visibility['pagination_info']) {
                    // Display pagination info
                    ?>
                    <div class="dk-container-pagination-info"><?php echo $Scaffolding->getPaginationInfo(); ?></div>
                    <?php
                }
                ?>
                </div>
                <div class="col-sm-6">
                <?php
                if ($visibility['pagination']) {
                    // Display pagination
                ?>
                    <div class="dk-container-pagination"><?php echo $Scaffolding->getPagination(); ?></div>
                <?php
                }
                ?>                  
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    {!! Form::close() !!}
</div>
