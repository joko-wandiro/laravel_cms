<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;

class CategoriesController extends BackEndController
{
    /**
     * Build categories page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
    	$Scaffolding= new Scaffolding("categories");
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'name',
	        	'width'=>'80%',
	        ),
			// Add Actions custom column
			array(
			    'name'=>'xaction',
			    'label'=>'Actions',
			    'width'=>'20%',
			    'callback'=>array($this, 'actionColumn'),
			),
        );
        $Scaffolding->setColumnProperties($parameters);
        $content= $Scaffolding->render();
        $parameters= $this->getParameters();
        $parameters['scaffolding']= $content;
        return view('backend.themes.standard.index', $parameters);
    }
}