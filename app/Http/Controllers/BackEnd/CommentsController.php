<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use DB;
use Validator;

class CommentsController extends BackEndController {

    /**
     * Get post list
     *
     * @return array
     */
    public function getPostList() {
        $Posts = new \App\Models\Posts;
        $list = array("" => trans('main.select.default'));
        $result = $Posts->get()->pluck("title", "id")->all();
        $list = $list + $result;
        return $list;
    }

    /**
     * Build comments page
     *
     * @return Illuminate\View\View
     */
    public function index() {
        $Request = request();
        $postList = $this->getPostList();
        $Scaffolding = new Scaffolding("comments");
        $Scaffolding->join('posts', 'posts.id', '=', 'comments.post', 'INNER');
        // Define form input filler for post
        $Scaffolding->setFormInputFiller("post", $postList);
        // Set columns properties
        $parameters = array(
            // Add checkbox for bulk action
            array(
                'name' => 'checkbox',
                'width' => '1%',
                'callback' => array($this, 'getCheckboxColumn'),
                'callbackColumn' => array($this, 'getLabelCheckboxColumn'),
            ),
            array(
                'name' => 'comment',
                'width' => '40%',
            ),
            array(
                'name' => "IF(comments.status=1,'Approve','Waiting for Approval') AS comment_status",
                'label' => 'Status',
                'width' => '20%',
            ),
            array(
                'name' => "comments.created_at AS created_at",
                'label' => 'Created',
                'width' => '20%',
            ),
            // Add Actions custom column
            array(
                'name' => 'custom_action',
                'label' => 'Actions',
                'width' => '19%',
                'callback' => array($this, 'actionColumn'),
            ),
        );
        $Scaffolding->setColumnProperties($parameters);
        $Scaffolding->orderBy('comments.id', 'DESC');
        // Set formatter for comment column
        $Scaffolding->addFormatterColumn('comment', array($this, 'formatterComment'));
        // Set formatter for status column
        $Scaffolding->addFormatterColumn('comment_status', array($this, 'formatterStatus'));
        // Set formatter for created_at column
        $Scaffolding->addFormatterColumn('created_at', array($this, 'formatterCreatedAt'));
        // Modify status form input in View action
        $Scaffolding->setFormInputView('status', array($this, 'getFormInputViewStatus'));
        // Modify comment form input in View action
        $Scaffolding->setFormInputView('comment', array($this, 'getFormInputViewComment'));
        // Define visibility elements in List View
        $visibility = array(
            'create_button' => FALSE,
        );
        $Scaffolding->setVisibilityListElements($visibility);
        // Add View for Bulk Action - approve, unapprove and delete
        $Scaffolding->addHooks("listFormStart", array($this, "getBulkAction"));
        $Scaffolding->orderBy("created_at", "DESC");
        $content = $Scaffolding->render();
        $parameters = $this->getParameters();
        $parameters['jsParameters'] = array(
            'urlBulk' => action(config('app.backend_namespace') . 'CommentsController@bulk'),
            'validationFailure' => trans('main.validation.failure'),
        );
        $parameters['scaffolding'] = $content;
        $view = 'backend.themes.standard.index';
        if (!$Request['action']) {
            $view = 'backend.themes.standard.comments';
        }
        return view($view, $parameters);
    }

    /**
     * Formatter for created_at column
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function formatterCreatedAt($model) {
        echo date("m/d/Y H:i", strtotime($model['created_at']->__toString()));
    }

    /**
     * Perform bulk action
     * 
     * @return Illuminate\View\View
     */
    public function bulk() {
        // Validate request
        $Request = request();
        $validationRules = array(
            'xbulkaction' => 'required|in:approve,unapprove,delete',
            'xselection.*' => 'required|numeric',
        );
        $validator = Validator::make($Request->all(), $validationRules);
        if ($validator->fails()) {
            return response()->json($validator->errors()->getMessages(), 400);
        }
        // Define fillable columns
        $Model = new \App\Models\Comments;
        $fillableColumns = array('status');
        $Model->fillable($fillableColumns);
        // Perform bulk action
        switch ($Request['xbulkaction']) {
            case "approve":
                $message = trans('main.comments.approve');
                $parameters = array(
                    'status' => 1,
                );
                $Model->whereIn('id', $Request['xselection'])->update($parameters);
                break;
            case "unapprove":
                $message = trans('main.comments.unapprove');
                $parameters = array(
                    'status' => 0,
                );
                $Model->whereIn('id', $Request['xselection'])->update($parameters);
                break;
            case "delete":
                $message = trans('main.comments.delete');
                $Model->whereIn('id', $Request['xselection'])->delete();
                break;
        }
        // Send Response
        $parameters = array(
            'message' => $message,
        );
        return response()->json($parameters, 200);
    }

    /**
     * Get label of checkbox column
     * 
     * @return  void
     */
    public function getLabelCheckboxColumn() {
        echo '<label>' . Form::checkbox('xmultiselection', '1') . '</label>';
    }

    /**
     * Get checkbox column
     * 
     * @param  \App\Libraries\Scaffolding\Model $record
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  void
     */
    public function getCheckboxColumn($record, $Scaffolding) {
        echo Form::checkbox('xselection[]', $record['id']);
    }

    /**
     * Get View for bulk action
     *
     * @return void
     */
    public function getBulkAction($Scaffolding) {
        echo view("comments.bulkaction")->render();
    }

    /**
     * Actions column
     * 
     * @param  \App\Libraries\Scaffolding\Model $record
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  void
     */
    public function actionColumn($record, $Scaffolding) {
        $url = $Scaffolding->getActionButtonUrls($record);
        echo '<div class="text-center">
	        <div class="btn-group">
	        	<a href="' . $url['view'] . '" class="btn btn-primary btn-view">View</a>
	        	<a href="' . $url['delete'] . '" class="btn btn-primary btn-remove">Remove</a>
	        </div>
	    </div>';
    }

    /**
     * Formatter for comment column
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function formatterComment($model) {
        echo nl2br($model['comment']);
    }

    /**
     * Formatter for status column
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function formatterStatus($model) {
        echo '<p><span class="label label-info">' . title_case($model['comment_status']) . '</span></p>';
    }

    /**
     * Get form input view for status
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function getFormInputViewStatus($column, $Model, $Scaffolding) {
        $value = ($Model['status']) ? 'Approve' : 'Waiting for Approval';
        echo '<div class="form-content">' . $value . '</div>';
    }

    /**
     * Get form input view for comment
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function getFormInputViewComment($column, $Model, $Scaffolding) {
        $value = nl2br($Model['comment']);
        echo '<div class="form-content">' . $value . '</div>';
    }

}
