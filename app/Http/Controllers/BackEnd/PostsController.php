<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Html;
use DB;
use App\Models\PostTags;
use App\Models\Tags;
use App\Models\Medias;

class PostsController extends BackEndController
{

    public $media;

    /**
     * Get category list
     *
     * @return array
     */
    public function getCategoryList()
    {
        $Model = new \App\Models\Categories;
        $list = array("" => trans('main.select.category'));
        $result = $Model->get()->pluck("name", "id")->all();
        $list = $list + $result;
        return $list;
    }

    /**
     * Get tag list
     *
     * @return array
     */
    public function getTagList()
    {
        $Model = new \App\Models\Tags;
        $result = $Model->get()->pluck("name", "id")->all();
        return $result;
    }

    /**
     * Build posts page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $Request = request();
        // Get media
        $this->media = Medias::gets();
        $categoryList = $this->getCategoryList();
        $tagList = $this->getTagList();
        $Scaffolding = new Scaffolding("posts");
        $Scaffolding->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
        // Define form input filler for category
        $Scaffolding->setFormInputFiller("category", $categoryList);
        // Define form input filler for tags
        $Scaffolding->setFormInputFiller("tags[]", $tagList);
        // Set columns properties
        $parameters = array(
            array(
                'name' => 'title',
                'width' => '50%',
            ),
            array(
                'name' => "IF(status=1,'published','draft') AS post_status",
                'label' => 'Status',
                'width' => '10%',
            ),
            array(
                'name' => "posts.created_at AS created_at",
                'label' => 'Created',
                'width' => '20%',
            ),
            // Add Actions custom column
            array(
                'name' => 'xaction',
                'label' => 'Actions',
                'width' => '20%',
                'callback' => array($this, 'actionColumn'),
            ),
        );
        $Scaffolding->setColumnProperties($parameters);
        // Set formatter for status column
        $Scaffolding->addFormatterColumn('post_status', array($this, 'formatterStatus'));
        // Set formatter for created_at column
        $Scaffolding->addFormatterColumn('created_at', array($this, 'formatterCreatedAt'));
        // Modify tags[] form input in View action
        $Scaffolding->setFormInputView('tags[]', array($this, 'getFormInputViewTags'));
        // Modify status form input in View action
        $Scaffolding->setFormInputView('status', array($this, 'getFormInputViewStatus'));
        // Modify published_at form input in View action
        $Scaffolding->setFormInputView('published_at', array($this, 'getFormInputViewPublishedAt'));
        // Modify validation rules
        $Scaffolding->addHooks("insertModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("updateModifyValidationRules", array($this, "modifyValidation"));
        // Hook Action to remove published_at column
        $Scaffolding->addHooks("insertModifyRequest", array($this, "setPublishedAt"));
        $Scaffolding->addHooks("updateModifyRequest", array($this, "setPublishedAt"));
        $Scaffolding->addHooks("modifyValidationRulesJS", array($this, "modifyValidationRulesJS"));
        // Modify form group input only
        $Scaffolding->setFormGroup('title', array($this, 'getFormGroupInputOnly'));
        $Scaffolding->setFormGroup('url', array($this, 'getFormGroupUrl'));
        $Scaffolding->setFormGroup('category', array($this, 'getFormGroupInputOnly'));
        $Scaffolding->setFormGroup('tags[]', array($this, 'getFormGroupInputOnly'));
        $Scaffolding->setFormGroup('content', array($this, 'getFormGroupInputOnly'));
        $Scaffolding->setFormGroup('published_at', array($this, 'getFormGroupInputOnly'));
        // Modify published_at form input
        $Scaffolding->setFormInput('published_at', array($this, 'getFormInputPublishedAt'));
        // Modify tags[] form input
        $Scaffolding->setFormInput('tags[]', array($this, 'getFormInputTags'));
        // Modify featured image form input
        $Scaffolding->setFormInput('id_featured_image', array($this, 'getFormInputFeaturedImage'));
        // Modify form layout
        $Scaffolding->addHooks("modifyLayout", array($this, "modifyFormLayout"));
        // Modify column properties
        $Scaffolding->addHooks("modifyColumnsProperties", array($this, "modifyFormColumns"));
        // Process tags
        $Scaffolding->addHooks("insertAfterInsert", array($this, "processTags"));
        $Scaffolding->addHooks("updateAfterUpdate", array($this, "processTags"));
        $Scaffolding->orderBy("created_at", "DESC");
        $content = $Scaffolding->render();
        $parameters = $this->getParameters();
        $parameters['scaffolding'] = $content;
        $parameters['medias'] = $this->media;
        switch ($Request['action']) {
            case "create":
            case "edit":
                $view = 'backend.themes.standard.posts';
                break;
            default:
                $view = 'backend.themes.standard.index';
                break;
        }
        return view($view, $parameters);
    }

    /**
     * Get form input tags
     * 
     * @param  array $column
     * @param  \App\Libraries\Scaffolding\Scaffolding $Scaffolding
     * 
     * @return  string
     */
    public function getFormInputTags($column, $Scaffolding)
    {
        // Get tags
        $Model = clone $Scaffolding->getModel();
        $postId = $Model['id'];
        $tags = array();
        if ($postId) {
            $Model = new PostTags;
            $tags = $Model->where('post_id', '=', $postId)->get()->pluck("tag_id")->all();
        }
        $filler = $Scaffolding->getFormInputFiller();
        $columnName = $column['name'];
        $values = isset($filler[$columnName]) ? $filler[$columnName] : array();
        $html = '<select name="' . $columnName . '" ' . Html::attributes($column['attributes']) . 'multiple="multiple">';
        foreach ($values as $key => $value) {
            $selected = ( in_array($key, $tags) ) ? 'selected="selected"' : "";
            $html .= '<option value="' . $key . '"' . $selected . '>' . e($value) . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    /**
     * Process tags
     * 
     * @param  array $columns
     * 
     * @return  array
     */
    public function processTags($Model)
    {
        $postId = $Model['id'];
        $parameters = request()->all();
        if (isset($parameters['tags'])) {
            $Model = new PostTags;
            // Delete previous tags
            $ModelDelete = clone $Model;
            $ModelDelete->where('post_id', '=', $postId)->delete();
            // Insert tags
            $insertParameters = array(
                'post_id' => $postId,
            );
            foreach ($parameters['tags'] as $tagId) {
                $insertParameters['tag_id'] = $tagId;
                $Model->create($insertParameters);
            }
        }
    }

    /**
     * Modify form columns
     * 
     * @param  array $columns
     * 
     * @return  array
     */
    public function modifyFormColumns($columns)
    {
        $columns['tags'] = array(
            'attributes' => array(
                'class' => 'form-control dk-number',
                'multiple' => 'multiple'
            ),
            'name' => 'tags[]',
            'label' => 'Tags',
            'dataType' => 'BIGINT',
            'length' => '20',
            'range' => 'unsigned',
            'type' => 'select',
            'require' => FALSE,
        );
        return $columns;
    }

    /**
     * Modify form layout
     * 
     * @param  array $layout
     * 
     * @return  array
     */
    public function modifyFormLayout($layout)
    {
        $tags = array(
            array(
                array(
                    'attributes' => array(
                        'class' => 'col-sm-12',
                    ),
                    'name' => 'tags',
                )
            )
        );
        array_splice($layout, 2, 0, $tags);
        return $layout;
    }

    public function modifyValidationRulesJS($rules)
    {
        unset($rules['published_at']['date']);
        return $rules;
    }

    /**
     * Modify validation
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidation($rules)
    {
        unset($rules['published_at']);
        $rules['published_at'] = 'date_format:m/d/Y g:i A|nullable';
        $rules['category'] .= '|exists:categories,id';
        $rules['id_featured_image'] .= '|exists:medias,id';
        return $rules;
    }

    /**
     * Get form input featured image
     * 
     * @param  array $column
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  string
     */
    public function getFormInputFeaturedImage($column, $Scaffolding)
    {
        $columnName = $column['name'];
        $Model = $Scaffolding->getModel();
        $id_media = $Model[$columnName];
        echo Form::hidden($column['name'], null, $column['attributes']);
        if ($id_media) {
            $record = $this->media[$id_media];
            ?>
            <p id="feature-image-preview"><img src="<?php echo image_url($record['image']); ?>" height="200"><a id="feature-image-btn-remove" href="#">Remove</a></p>
            <?php
        }
        ?>
        <p><a href="#" id="feature-image"><?php echo trans('main.set_featured_image'); ?></a></p>
        <?php
    }

    /**
     * Get form input published_at
     * 
     * @param  array $column
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  string
     */
    public function getFormInputPublishedAt($column, $Scaffolding)
    {
        $columnName = $column['name'];
        $Model = $Scaffolding->getModel();
        if ($Model[$columnName]) {
            $Model[$columnName] = date("m/d/Y g:i A", strtotime($Model[$columnName]));
        }
        echo '<div class="input-group date" id="datetimepicker1">';
        echo Form::text($column['name'], null, $column['attributes']);
        echo '<span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>';
    }

    /**
     * Get form group url
     * 
     * @param  array $column
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  string
     */
    public function getFormGroupUrl($column, $Scaffolding)
    {
        echo '<div class="form-group">
		<div class="input-group">
		<span class="input-group-addon">' . url('news') . '/</span>' .
        $Scaffolding->getFormInput($column) . '</div>';
        echo $Scaffolding->getFormInputError($column['name']);
        echo '</div>';
    }

    /**
     * Get form group input only
     * 
     * @param  array $column
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  string
     */
    public function getFormGroupInputOnly($column, $Scaffolding)
    {
        echo '<div class="form-group">';
        echo $Scaffolding->getFormInput($column);
        echo $Scaffolding->getFormInputError($column['name']);
        echo '</div>';
    }

    /**
     * Set published_at column
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function setPublishedAt($parameters)
    {
        $parameters['published_at'] = ($parameters['published_at']) ? date("Y-m-d H:i:s", strtotime($parameters['published_at'])) : null;
        return $parameters;
    }

    /**
     * Formatter for created_at column
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function formatterCreatedAt($model)
    {
        echo date("m/d/Y H:i", strtotime($model['created_at']->__toString()));
    }

    /**
     * Formatter for status column
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function formatterStatus($model)
    {
        echo '<p><span class="label label-info">' . title_case($model['post_status']) . '</span></p>';
    }

    /**
     * Get form input view for tags
     * 
     * @param array $column
     * @param \App\Libraries\Scaffolding\Model $Model
     * @param \App\Libraries\Scaffolding\Scaffolding $Scaffolding
     * 
     * @return void
     */
    public function getFormInputViewTags($column, $Model, $Scaffolding)
    {
        // Get tags
        $Model = clone $Scaffolding->getModel();
        $postId = $Model['id'];
        $Model = new PostTags;
        $tagIds = $Model->where('post_id', '=', $postId)->get()->pluck("tag_id")->all();
        $Model = new Tags;
        $tags = $Model->whereIn('id', $tagIds)->get()->pluck("name")->all();
        echo '<div class="form-content">' . implode($tags, ", ") . '</div>';
    }

    /**
     * Get form input view for status
     * 
     * @param \App\Libraries\Scaffolding\Model $model
     * 
     * @return void
     */
    public function getFormInputViewStatus($column, $Model, $Scaffolding)
    {
        $value = ($Model['status']) ? 'Published' : 'Draft';
        echo '<div class="form-content">' . $value . '</div>';
    }

    /**
     * Get form input view for published_at
     * 
     * @param array $column
     * @param \App\Libraries\Scaffolding\Model $Model
     * @param \App\Libraries\Scaffolding\Scaffolding $Scaffolding
     * 
     * @return void
     */
    public function getFormInputViewPublishedAt($column, $Model, $Scaffolding)
    {
        echo '<div class="form-content">' .
        date("m/d/Y g:i A", strtotime($Model['published_at'])) . '</div>';
    }

}
