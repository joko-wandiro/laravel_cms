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

class PagesController extends BackEndController
{

    /**
     * Build pages page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        // Get media
        $this->media = Medias::gets();
        $Scaffolding = new Scaffolding("pages");
        // Set columns properties
        $parameters = array(
            array(
                'name' => 'title',
                'width' => '50%',
            ),
            array(
                'name' => "IF(status=1,'published','draft') AS page_status",
                'label' => 'Status',
                'width' => '10%',
            ),
            array(
                'name' => "pages.created_at AS created_at",
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
        // Modify form group input only
        $Scaffolding->setFormGroup('title', array($this, 'getFormGroupInputOnly'));
        $Scaffolding->setFormGroup('url', array($this, 'getFormGroupUrl'));
        $Scaffolding->setFormGroup('content', array($this, 'getFormGroupInputOnly'));
        // Modify featured image form input
        $Scaffolding->setFormInput('id_featured_image', array($this, 'getFormInputFeaturedImage'));
        // Modify validation rules
        $Scaffolding->addHooks("insertModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("updateModifyValidationRules", array($this, "modifyValidation"));        
        $Scaffolding->orderBy("created_at", "DESC");
        $content = $Scaffolding->render();
        $parameters = $this->getParameters();
        $parameters['scaffolding'] = $content;
        $parameters['medias'] = $this->media;
        return view('backend.themes.standard.pages', $parameters);
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
		<span class="input-group-addon">' . url('') . '/</span>' .
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

}
