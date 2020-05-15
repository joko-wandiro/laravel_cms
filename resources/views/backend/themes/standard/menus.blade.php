@extends('backend.themes.standard.default')

@push('styles')
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    /**
     * Nestable
     */

    .dd { position: relative; display: block; margin: 0; padding: 0; max-width: 600px; list-style: none; font-size: 13px; line-height: 20px; }

    .dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
    .dd-list .dd-list { padding-left: 30px; }
    .dd-collapsed .dd-list { display: none; }

    .dd-item,
    .dd-empty,
    .dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }

    .dd-handle { display: block; height: 30px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
                 background: #fafafa;
                 background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
                 background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
                 background:         linear-gradient(top, #fafafa 0%, #eee 100%);
                 -webkit-border-radius: 3px;
                 border-radius: 3px;
                 box-sizing: border-box; -moz-box-sizing: border-box;
    }
    .dd-handle:hover { color: #2ea8e5; background: #fff; }

    .dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
    .dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
    .dd-item > button[data-action="collapse"]:before { content: '-'; }

    .dd-placeholder,
    .dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
    .dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
                background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                    -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
                background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
                background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                    linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
                background-size: 60px 60px;
                background-position: 0 0, 30px 30px;
    }

    .dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
    .dd-dragel > .dd-item .dd-handle { margin-top: 0; }
    .dd-dragel .dd-handle {
        -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
        box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
    }
    /**
     * Nestable Draggable Handles
     */

    .dd3-content { display: block; height: 30px; margin: 5px 0; padding: 5px 10px 5px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
                   background: #fafafa;
                   background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
                   background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
                   background:         linear-gradient(top, #fafafa 0%, #eee 100%);
                   -webkit-border-radius: 3px;
                   border-radius: 3px;
                   box-sizing: border-box; -moz-box-sizing: border-box;
    }
    .dd3-content:hover { color: #2ea8e5; background: #fff; }

    .dd-dragel > .dd3-item > .dd3-content { margin: 0; }

    .dd3-item > button { margin-left: 30px; }

    .dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;
                  border: 1px solid #aaa;
                  background: #ddd;
                  background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
                  background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
                  background:         linear-gradient(top, #ddd 0%, #bbb 100%);
                  border-top-right-radius: 0;
                  border-bottom-right-radius: 0;
    }
    .dd3-handle:before { content: '≡'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
    .dd3-handle:hover { background: #ddd; }    
    #sortable1, #sortable2 {
        border: 1px solid #eee;
        width: auto;
        min-height: 40px;
        list-style-type: none;
        margin: 0px 0px 20px 0px;
        padding: 10px;
        margin-right: 10px;
    }
    .btn-menu-remove {
        float: right;
        font-size: 10px;
        padding: 0px;
        line-height: 12px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><?php echo trans('main.dashboard'); ?></h1>
</div>
<div class="dashboard-information">
    <div class="row">
        <div class="col-sm-3">
            <ul id="sortable1" class="connectedSortable">
                <?php
                foreach ($pages as $page) {
                    if (!in_array($page['id'], $menu_ids)) {
                        ?>
                        <li class="ui-state-default">
                            <label>
                                <input type="checkbox" name="pages[]" value="<?php echo $page['id']; ?>" data-title="<?php echo $page['title']; ?>" /><?php echo $page['title']; ?></label>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
            <button id="btn-add-to-menu"><?php echo trans('main.add.to.menu'); ?></button>
        </div>
        <div class="col-sm-3">
            {{ Form::open(array('url' => $url, 'method'=>'POST')) }}
            <div class="dd" id="nestable3">
                <?php echo $list_html; ?>
            </div>
            <div>
                <input type="submit" name="submit" value="Submit" />
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="<?php echo url('js/jquery.nestable.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/themes/standard/site.js'); ?>"></script>
<script type="text/javascript" src="<?php echo url('js/backend/menus.js'); ?>"></script>
@endpush