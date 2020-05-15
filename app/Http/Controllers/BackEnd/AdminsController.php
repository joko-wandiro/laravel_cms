<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Illuminate\Http\JsonResponse;

class AdminsController extends BackEndController
{

    protected $table = 'admins';

    /**
     * Build admins page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $Scaffolding = new Scaffolding("admins");
        $Scaffolding->setTitle(trans('main.users'));
        // Set columns properties
        $parameters = array(
            array(
                'name' => 'name',
                'width' => '40%',
            ),
            array(
                'name' => 'email',
                'width' => '40%',
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
        // Set password
        $Scaffolding->addHooks("insertModifyRequest", array($this, "setPassword"));
        $Scaffolding->addHooks("updateModifyRequest", array($this, "setPassword"));
        // Modify properties of columns
        $Scaffolding->addHooks("modifyColumnsProperties", array($this, "modifyColumnsProperties"));
        // Modify validation rules
        $Scaffolding->addHooks("insertModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("updateModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("modifyValidationRulesJS", array($this, "modifyValidationRulesJS"));
        // Modify form layout
        $Scaffolding->addHooks("modifyLayout", array($this, "modifyLayout"));
        // Hooks Action for delete operation ( AJAX Request )
        $Scaffolding->addHooks("deleteModifyResponse", array($this, "deleteModifyResponse"));
        $content = $Scaffolding->render();
        $parameters = $this->getParameters();
        $parameters['scaffolding'] = $content;
        return view('backend.themes.standard.index', $parameters);        
    }

    /**
     * Modify form layout
     * 
     * @param  array $layout
     * 
     * @return  array
     */
    public function modifyLayout($layout)
    {
        $layout[] = array(
            array(
                'attributes' => array(
                    'class' => 'col-sm-12',
                ),
                'name' => 'password_confirm',
            ),
        );
        return $layout;
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
        $action = request()->action;
        if ($action == "edit") {
            unset($rules['password']['required']);
        }
        $rules['password_confirm']['equalTo'] = ":input[name=\"password\"]";
        return $rules;
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
        $rules['password_confirm'] = 'max:255|string|required|same:password';
        if ($httpVerb == "PUT" || $httpVerb == "PATCH") {
            $rules['password'] = 'max:255|string|nullable';
            $rules['password_confirm'] = 'max:255|string|nullable|same:password';
        }
        return $rules;
    }

    /**
     * Modify properties of columns
     * 
     * @param  array $columns
     * 
     * @return  array
     */
    public function modifyColumnsProperties($columns)
    {
        $action = request()->action;
        $columns['password_confirm'] = array(
            'attributes' => array(
                'class' => 'form-control dk-character',
                'placeholder' => 'Password',
            ),
            'name' => 'password_confirm',
            'label' => trans('dkscaffolding.column.password_confirm'),
            'dataType' => 'VARCHAR',
            'length' => '255',
            'range' => NULL,
            'type' => 'password',
            'require' => true,
        );
        if ($action == "edit") {
            $columns['password']['require'] = false;
            $columns['password_confirm']['require'] = false;
        }
        return $columns;
    }

    /**
     * Set image column
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function setPassword($parameters)
    {
        $httpVerb = request()->getMethod();
        if (($httpVerb == "PUT" || $httpVerb == "PATCH") && !$parameters['password']) {
            unset($parameters['password']);
        }
        if (isset($parameters['password'])) {
            $parameters['password'] = password_hash($parameters['password'], PASSWORD_DEFAULT);
        }
        return $parameters;
    }

}
