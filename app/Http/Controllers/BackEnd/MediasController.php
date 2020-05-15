<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

class MediasController extends BackEndController
{

    /**
     * Build categories page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $Scaffolding = new Scaffolding("medias");
        // Set columns properties
        $parameters = array(
            array(
                'name' => 'name',
                'width' => '20%',
            ),
            array(
                'name' => 'image',
                'width' => '40%',
            ),
            // Add Actions custom column
            array(
                'name' => 'xurl',
                'label' => 'Url',
                'width' => '20%',
                'callback' => array($this, 'urlColumn'),
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
        // Upload file to temporary folder and set it into parameters
        $Scaffolding->addHooks("insertModifyRequest", array($this, "setImage"));
        $Scaffolding->addHooks("updateModifyRequest", array($this, "setImage"));
        // Upload file to permanent folder
        $Scaffolding->addHooks("insertAfterInsert", array($this, "moveImage"));
        $Scaffolding->addHooks("updateAfterUpdate", array($this, "moveImage"));
        // Modify image form input
        $Scaffolding->setFormInput('image', array($this, 'getFormInputImage'));
        // Modify validation rules
        $Scaffolding->addHooks("insertModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("updateModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("modifyValidationRulesJS", array($this, "modifyValidationRulesJS"));
        // Set formatter for image column
        $Scaffolding->addFormatterColumn('image', array($this, 'formatterImage'));
        // Modify column properties
        $Scaffolding->addHooks("modifyColumnsProperties", array($this, "modifyFormColumns"));
        $content = $Scaffolding->render();
        $parameters = $this->getParameters();
        $parameters['scaffolding'] = $content;
        return view('backend.themes.standard.index', $parameters);
    }

    /**
     * Url column
     * 
     * @param  \App\Libraries\Scaffolding\Model $record
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  void
     */
    public function urlColumn($record, $Scaffolding)
    {
        echo '<p><span class="label label-info">' . image_url($record['image']) . '</span></p>';
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
        if (request('action') == "edit") {
            unset($columns['image']);
        }
        return $columns;
    }

    /**
     * Formatter for image column
     * 
     * @param  \App\Libraries\Scaffolding\Model $model
     * 
     * @return  void
     */
    public function formatterImage($model)
    {
        if ($model['image']) {
            $pathinfos = pathinfo($model['image']);
            echo '<p><img src="' . image_url($pathinfos['filename'] . '_small.' . $pathinfos['extension']) . '" /></p>';
        } else {
            echo '-';
        }
    }

    /**
     * Modify validation
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidation($rules)
    {
        $httpVerb = request()->getMethod();
        if ($httpVerb == "POST") {
            $rules['image'] = 'file|mimetypes:image/png,image/jpeg|required';
        }
        if ($httpVerb == "PUT") {
            unset($rules['image']);
        }
        return $rules;
    }

    /**
     * Modify javascript validation rules
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidationRulesJS($rules)
    {
        unset($rules['image']['maxlength']);
        $rules['image']['accept'] = "image/png,image/jpeg";
        return $rules;
    }

    /**
     * Set image column
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function setImage($parameters)
    {
        $Request = request();
        // Upload photo file
        $filename = null;
        $hasImage = $Request->hasFile('image');
        if ($hasImage) {
            // Upload new file
            $destinationPath = images_temporary_path();
            $file = $Request->file('image');
            $fileExtension = $file->getClientOriginalExtension();
            $filename = get_unique_filename() . '.' . $fileExtension;
            $fullPath = $destinationPath . $filename;
            while (file_exists($fullPath)) {
                $filename = get_unique_filename() . '.' . $fileExtension;
                $fullPath = $destinationPath . $filename;
            }
            $status = $file->move($destinationPath, $filename); // uploading file to given path
            $Request->files->remove('image');
            $parameters['image'] = $filename;
        }
        return $parameters;
    }

    /**
     * Move image
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function moveImage($Model)
    {
        $Request = request();
        $old = unserialize($Request['idx_old']);
        $filename = null;
        $hasImage = $Request->hasFile('image');
        if ($hasImage) {
            // Move image file to permanent directory
            $image_path = images_path($Model->image);
            rename(images_temporary_path($Model->image), $image_path);
            $imagine = new Imagine();
            $pathinfos = pathinfo($image_path);
            // Small size
            $image = $imagine->open($image_path);
            $image->resize(new Box(settings('image_size_small_width'), settings('image_size_small_height')))
                    ->save(images_path($pathinfos['filename'] . '_small.' . $pathinfos['extension']));
            // Medium size
            $image = $imagine->open($image_path);
            $image->resize(new Box(settings('image_size_medium_width'), settings('image_size_medium_height')))
                    ->save(images_path($pathinfos['filename'] . '_medium.' . $pathinfos['extension']));
            // Delete previous file
            if ($old['image']) {
                $pathinfos_old = pathinfo($image_path);
                unlink(images_path($old['image']), images_path($pathinfos_old['filename'] . '_small.' . $pathinfos['extension']), images_path($pathinfos_old['filename'] . '_medium.' . $pathinfos['extension']));
            }
        }
    }

    /**
     * Get form input image
     * 
     * @param  array $column
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  string
     */
    public function getFormInputImage($column, $Scaffolding)
    {
        echo Form::file($column['name'], $column['attributes']);
    }

}
