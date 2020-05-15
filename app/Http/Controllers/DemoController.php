<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;

class DemoController extends Controller
{

    /**
     * Scaffolding Demo - Installation
     *
     * @return Illuminate\View\View
     */
    public function installation()
    {
    	return view('backend.themes.standard.installation');
    }
    
    /**
     * Scaffolding Demo - Simple
     *
     * @return Illuminate\View\View
     */
    public function demo_simple()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.simple')->render()));
    	// Define specific table
		$tableCategories= $Scaffolding->getTable("categories");
        return $Scaffolding->render();
    }
    
    /**
     * Scaffolding Demo - Set columns manually
     *
     * @return Illuminate\View\View
     */
    public function demo_set_columns_manually()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.set_columns_manually')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
		// Set columns manually
        $columns       = array('no', 'name', 'actions');
        $tableCategories->setColumns($columns);
        return $Scaffolding->render();
    }

    /**
     * Scaffolding Demo - Alias columns
     *
     * @return Illuminate\View\View
     */
    public function demo_alias_columns()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.alias_columns')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
		// Set columns manually
        $columns       = array('name', 'CHAR_LENGTH(name) AS length', 'actions');
        $tableCategories->setColumns($columns);
        return $Scaffolding->render();
    }
    
    /**
     * Scaffolding Demo - Custom columns
     *
     * @return Illuminate\View\View
     */
    public function demo_custom_columns()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.custom_columns')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
        // Set columns manually
        $columns       = array('no', 'name', 'actions');
        $tableCategories->setColumns($columns);
        // Add custom column status
        $statusColumn= array(
	        'name'=>'status',
	        'callback'=>array($this, 'statusColumn'),
        );
        $tableCategories->addCustomColumnAfterColumn('name', $statusColumn);
//        $tableCategories->addCustomColumn($statusColumn);
//        $tableCategories->addCustomColumnAsFirstColumn($statusColumn);
        return $Scaffolding->render();
    }

    /**
	* Status column
	* 
	* @return string
	*/
    public function statusColumn()
    {
    	?>
    	<span class="label label-info">active</span>
    	<?php
    }
    
    /**
     * Scaffolding Demo - Formatter columns
     *
     * @return Illuminate\View\View
     */
    public function demo_formatter_columns()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.formatter_columns')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
        // Set columns manually
        $columns       = array('no', 'name', 'actions');
        $tableCategories->setColumns($columns);
        // Set formatter for name column
        $tableCategories->addFormatterColumn('name', array($this, 'formatterName'));
        return $Scaffolding->render();
    }

	/**
	* Formatter for title column
	* 
	* @param \App\Libraries\Scaffolding\Model $model
	* 
	* @return string
	*/
    public function formatterName($model)
    {
		echo '<p class="text-primary">' . $model['name'] . '</p>';
    }
    
    /**
     * Scaffolding Demo - Set columns properties
     *
     * @return Illuminate\View\View
     */
    public function demo_set_columns_properties()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.set_columns_properties')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'name',
	        	'width'=>'79%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tableCategories->setColumnProperties($parameters);
        return $Scaffolding->render();
    }
    
    /**
     * Scaffolding Demo - Add custom action buttons
     *
     * @return Illuminate\View\View
     */
    public function demo_add_custom_action_buttons()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.add_custom_action_buttons')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'name',
	        	'width'=>'89%',
	        ),
	        // Add Actions custom column
	        array(
	        	'name'=>'custom_action',
	        	'label'=>'Actions',
	        	'width'=>'10%',
	        	'callback'=>array($this, 'actionColumn'),
	        ),
        );
        $tableCategories->setColumnProperties($parameters);
        return $Scaffolding->render();
    }

	/**
	* Actions column
	* 
	* @param \App\Libraries\Scaffolding\Model $record
	* @param \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
	* 
	* @return void
	*/
    public function actionColumn($record, $Scaffolding)
    {
    	$url= $Scaffolding->getActionButtonUrls($record);
    	echo '<div class="text-center">
	        <div class="btn-group">
	            <a href="' . $url['view'] . '" class="btn btn-primary"><i class="glyphicon glyphicon glyphicon-eye-open"></i></a>
	            <a href="' . $url['edit'] . '" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i></a>
	        </div>
        </div>';
    }
    
    /**
     * Scaffolding Demo - Visibility elements in List View
     *
     * @return Illuminate\View\View
     */
    public function demo_visibility_list_elements()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.visibility_list_elements')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
		// Define visibility elements in List View
		$visibility= array(
			'create_button'=>FALSE,
			'multi_search'=>FALSE,
			'single_search'=>FALSE,
			'submit_button'=>FALSE,
//			'records_per_page'=>FALSE,
//			'pagination_info'=>FALSE,
//			'pagination'=>FALSE,
		);
        $tableCategories->setVisibilityListElements($visibility);
        return $Scaffolding->render();
    }

    /**
     * Scaffolding Demo - Custom search
     *
     * @return Illuminate\View\View
     */
    public function demo_custom_search()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.custom_search')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'name',
	        	'width'=>'79%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tableCategories->setColumnProperties($parameters);
		$visibility= array(
			'multi_search'=>FALSE,
			'single_search'=>FALSE,
			'submit_button'=>FALSE,
		);
        $tableCategories->setVisibilityListElements($visibility);
        $tableCategories->addHooks("listModifySearch", array($this, "processCustomSearch"));
        $tableCategories->addHooks("listFormStart", array($this, "getCustomCategoriesSearch"));
        return $Scaffolding->render();
    }

    /**
     * Process custom search
     *
     * @return \App\Libraries\Scaffolding\Model
     */
    public function processCustomSearch($Model)
    {
    	$Request= request();
    	$operator= '=';
    	$value= $Request['xsearch']['value'];
    	$operator= $Request['xsearch']['operator'];
    	if( $value && $operator ){
	    	switch($Request['xsearch']['operator']){
				case "eq":
					$operator= "=";
					break;
				case "ne":
					$operator= "!=";
					break;
				case "lt":
					$operator= "<";
					break;
				case "le":
					$operator= "<=";
					break;
				case "gt":
					$operator= ">";
					break;
				case "ge":
					$operator= ">=";
					break;
				case "ct":
					$operator= "LIKE";
					$value= '%'.$value.'%';
					break;
			}
			$Model= $Model->where('categories.name', $operator, $value);			
		}
		return $Model;
    }
    
    /**
     * Get custom categories search
     *
     * @return string
     */
    public function getCustomCategoriesSearch($Scaffolding)
    {
    	echo view("categories.search")->render();
    }

    /**
     * Get category list
     *
     * @return array
     */
    public function getCategoryList()
    {
    	$Categories= new \App\Models\Categories;
    	$categoryList= array(""=>"-- Select --");
    	$result= $Categories->get()->pluck("name", "id")->all();
    	$categoryList= $categoryList + $result;
    	return $categoryList;
    }
    
    /**
     * Scaffolding Demo - Input form filler
     *
     * @return Illuminate\View\View
     */
    public function demo_input_form_filler()
    {
    	$categoryList= $this->getCategoryList();
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.input_form_filler')->render()));
		$tablePosts= $Scaffolding->getTable("posts");
		$tablePosts->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
		// Define form input filler for category
		$tablePosts->setFormInputFiller("category", $categoryList);
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'categories.name',
	        	'label'=>'Category',
	        	'width'=>'30%',
	        ),
	        array(
	        	'name'=>'title',
	        	'width'=>'49%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tablePosts->setColumnProperties($parameters);
        return $Scaffolding->render();
    }
    
    /**
     * Get category list fake
     *
     * @return array
     */
    public function getCategoryListFake()
    {
    	$Categories= new \App\Models\Categories;
    	$categoryList= array(""=>"-- Select --", "99999999"=>"Fakel");
    	$result= $Categories->get()->pluck("name", "id")->all();
    	$categoryList= $categoryList + $result;
    	return $categoryList;
    }
    
    /**
     * Scaffolding Demo - Custom validation
     *
     * @return Illuminate\View\View
     */
    public function demo_custom_validation()
    {
    	$categoryList= $this->getCategoryListFake();
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.custom_validation')->render()));
		$tablePosts= $Scaffolding->getTable("posts");
		$tablePosts->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
		// Define form input filler for category
		$tablePosts->setFormInputFiller("category", $categoryList);
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'categories.name',
	        	'label'=>'Category',
	        	'width'=>'30%',
	        ),
	        array(
	        	'name'=>'title',
	        	'width'=>'49%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tablePosts->setColumnProperties($parameters);
        // Modify validation rules
        $tablePosts->addHooks("insertModifyValidationRules", 
        array($this, "modifyCategoryValidation"));
        return $Scaffolding->render();
    }

    /**
	* Modify category validation
	* 
	* @param array $rules
	* 
	* @return array
	*/
    public function modifyCategoryValidation($rules)
    {
    	$rules['category'].= '|exists:categories,id';
    	return $rules;
    }

    /**
     * Scaffolding Demo - Modify validation label error
     *
     * @return Illuminate\View\View
     */
    public function demo_modify_validation_label_error()
    {
    	$categoryList= $this->getCategoryListFake();
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.modify_validation_label_error')->render()));
		$tablePosts= $Scaffolding->getTable("posts");
		$tablePosts->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
		// Define form input filler for category
		$tablePosts->setFormInputFiller("category", $categoryList);
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'categories.name',
	        	'label'=>'Category',
	        	'width'=>'30%',
	        ),
	        array(
	        	'name'=>'title',
	        	'width'=>'49%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tablePosts->setColumnProperties($parameters);
        // Modify validation rules
        $tablePosts->addHooks("insertModifyValidationRules", 
        array($this, "modifyCategoryValidation"));
        // Modify validation label error
        $tablePosts->addHooks('modifyLabelFormInputError', 
        array($this, 'modifyLabelFormInputError'));
        return $Scaffolding->render();
    }

	/**
	* Modify label form input error
	* 
	* @param string $labelError
	* 
	* @return string
	*/
    public function modifyLabelFormInputError($labelError)
    {
    	$labelError= '<label class="error"><span class="glyphicon glyphicon-info-sign"></span> %1$s</label>';
    	return $labelError;
    }
        
    /**
     * Scaffolding Demo - Custom form layout
     *
     * @return Illuminate\View\View
     */
    public function demo_custom_form_layout()
    {
    	$categoryList= $this->getCategoryList();
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.custom_form_layout')->render()));
		$tablePosts= $Scaffolding->getTable("posts");
		$tablePosts->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
		$tablePosts->setFormInputFiller("category", $categoryList);
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'categories.name',
	        	'label'=>'Category',
	        	'width'=>'30%',
	        ),
	        array(
	        	'name'=>'title',
	        	'width'=>'49%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tablePosts->setColumnProperties($parameters);
        // Modify form layout
        $tablePosts->addHooks("modifyLayout", array($this, "modifyFormLayout"));
        return $Scaffolding->render();
    }

    /**
	* Modify form layout
	* 
	* @param array $layout
	* 
	* @return array
	*/
    public function modifyFormLayout($layout)
    {
    	$newLayout= array();
    	$newLayout[]= array(
	    	array(
				'attributes' => array(
					'class' => 'col-sm-6',
				),
				'name' => 'title',
			),
	    	array(
				'attributes' => array(
					'class' => 'col-sm-6',
				),
				'name' => 'category',
			),
		);
    	$newLayout[]= array(
	    	array(
				'attributes' => array(
					'class' => 'col-sm-12',
				),
				'name' => 'description',
			),
		);
    	return $newLayout;
    }
    
    /**
     * Scaffolding Demo - Custom form columns
     *
     * @return Illuminate\View\View
     */
    public function demo_custom_form_columns()
    {
    	$categoryList= $this->getCategoryList();
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.custom_form_columns')->render()));
		$tablePosts= $Scaffolding->getTable("posts");
		$tablePosts->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
		$tablePosts->setFormInputFiller("category", $categoryList);
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'categories.name',
	        	'label'=>'Category',
	        	'width'=>'30%',
	        ),
	        array(
	        	'name'=>'title',
	        	'width'=>'49%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tablePosts->setColumnProperties($parameters);
        $tablePosts->addHooks("modifyColumnsProperties", array($this, "modifyFormColumns"));
        return $Scaffolding->render();
    }

    /**
	* Modify form columns
	* 
	* @param array $columns
	* 
	* @return array
	*/
    public function modifyFormColumns($columns)
    {
    	$columns['description']['type']= 'text';
    	return $columns;
    }
    
    /**
     * Scaffolding Demo - Custom form input
     *
     * @return Illuminate\View\View
     */
    public function demo_custom_form_input()
    {
    	$categoryList= $this->getCategoryList();
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.custom_form_input')->render()));
		$tablePosts= $Scaffolding->getTable("posts");
		$tablePosts->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
		$tablePosts->setFormInputFiller("category", $categoryList);
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'categories.name',
	        	'label'=>'Category',
	        	'width'=>'30%',
	        ),
	        array(
	        	'name'=>'title',
	        	'width'=>'49%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tablePosts->setColumnProperties($parameters);
        // Modify description form input
        $tablePosts->setFormInput('description', array($this, 'getFormInputDescription'));
        return $Scaffolding->render();
    }
    
	/**
	* Get form input description
	* 
	* @param array $column
	* @param \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
	* 
	* @return string
	*/
    public function getFormInputDescription($column, $Scaffolding)
    {
    	echo Form::text('description', null, $column['attributes']);
    }
    
    /**
     * Scaffolding Demo - Custom form input view
     *
     * @return Illuminate\View\View
     */
    public function demo_custom_form_input_view()
    {
    	$categoryList= $this->getCategoryList();
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.custom_form_input_view')->render()));
		$tablePosts= $Scaffolding->getTable("posts");
		$tablePosts->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
		$tablePosts->setFormInputFiller("category", $categoryList);
        // Set columns properties
        $parameters= array(
	        array(
	        	'name'=>'no',
	        	'width'=>'1%',
	        ),
	        array(
	        	'name'=>'categories.name',
	        	'label'=>'Category',
	        	'width'=>'30%',
	        ),
	        array(
	        	'name'=>'title',
	        	'width'=>'49%',
	        ),
	        array(
	        	'name'=>'actions',
	        	'width'=>'20%',
	        ),
        );
        $tablePosts->setColumnProperties($parameters);
        // Modify title form input in View action
        $tablePosts->setFormInputView('title', array($this, 'getFormInputViewTitle'));
        return $Scaffolding->render();
    }

	/**
	* Get form input view title
	* 
	* @param array $column
	* @param \App\Libraries\Scaffolding\Model $Model
	* @param \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
	* 
	* @return string
	*/
    public function getFormInputViewTitle($column, $Model, $Scaffolding)
    {
    	$columnName= $column['name'];
    	$value= $Model[$columnName];
    	echo '<div class="form-content"><p class="text-primary">' . $value . '</p></div>';
    }

    /**
     * Scaffolding Demo - Join tables
     *
     * @return Illuminate\View\View
     */
    public function demo_join_tables()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.join_tables')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
        $tableCategories->join('posts', 'posts.category', '=', 'categories.id', 'INNER');
        // Set columns manually
        $columns       = array('name', 'posts.title AS post_title');
        $tableCategories->setColumns($columns);
        return $Scaffolding->render();
    }
    
    /**
     * Scaffolding Demo - Multi table
     *
     * @return Illuminate\View\View
     */
    public function demo_multi_table()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.multi_table')->render()));
		$tableCategories= $Scaffolding->getTable("categories");
		$tablePosts= $Scaffolding->getTable("posts");
        return $Scaffolding->render();
    }
    
    /**
     * Scaffolding Demo - Multiple same table
     *
     * @return Illuminate\View\View
     */
    public function demo_multiple_same_table()
    {
    	$Scaffolding= new Scaffolding();
    	$Scaffolding->setMasterTemplate('backend.themes.standard.index', array('description'=>view('demo.multiple_same_table')->render()));
		$tableFirstCategories= $Scaffolding->getTable("categories", "first_categories");
		$tableSecondCategories= $Scaffolding->getTable("categories", "second_categories");
        return $Scaffolding->render();
    }
    
    /**
     * Scaffolding Demo - Available Hooks Action and Filter
     *
     * @return Illuminate\View\View
     */
    public function availableHooks()
    {
    	return view('backend.themes.standard.availablehooks');
    }
}