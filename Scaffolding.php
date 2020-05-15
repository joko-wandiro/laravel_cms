<?php

namespace Scaffolding;

//use Illuminate\Http\Request as Request;
//use App\Http\Requests;
//use App\Http\Controllers\Controller;
use Route;
use Form;
//use DB;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Http\JsonResponse;
use Scaffolding\Builder as ScaffoldingBuilder;
use Valitron\Validator;

class Scaffolding {

    protected $type = null;
    protected $Route;
    protected $actionName;
    protected $Request;
    protected $Response;
    protected $controller = null;
    protected $Model;
    protected $table;
    protected $title = null;
    protected $indexes = array();
    // Default pagination
    protected $pagination = null;

    /**
     * List of records per page
     * @var array
     */
    protected $listOfRecordsPerPage = array();
    // Column properties
    protected $columnProperties = array();
    // Column names
    protected $columnNames = array();
    // Column relations
    protected $columnRelations = null;
    // Default columns
    protected $defaultColumns = null;
    // Columns
    protected $columns = null;
    // Columns information
    protected $columnsInformation = null;
    protected $orders = array();
    protected $builtInCustomColumns = array(
        'no' => array(
            'name' => 'no',
            'label' => 'No',
            'callback' => array(__CLASS__, 'getSequenceNumber'),
        ),
        'actions' => array(
            'name' => 'actions',
            'label' => 'Actions',
            'callback' => array(__CLASS__, 'getActionButtons'),
        ),
    );
    protected $customColumns = array();
    protected $formatterColumns = array();
    protected $sortableColumns = array();

    /**
     * Template directory
     *
     * @var string
     */
    protected $template = 'dkscaffolding';
    protected $actionButtons = array("view", "edit", "delete");
    public $sequenceNumber = 0;
    // Set default pagination info visibility
    protected $paginationInfoVisibility = true;
    protected $aliases = array();
    protected $identifier = null;

    /**
     * Group by
     * @var array
     */
    protected $groupBy = array();
    // Parameters for default order
    protected $order = null;
    protected $orderType = "DESC";

    /**
     * snakecase string
     */
    const SNAKECASE = "snakecase";

    /**
     * camelcase string
     */
    const CAMELCASE = "camelcase";

    /**
     * Case Type of column name
     *
     * @var string
     */
    public $columnNameCase = self::SNAKECASE;

    /**
     * Prefix to be added with column name for translate file
     *
     * @var string
     */
    protected $prefixTranslation;

    /**
     * Is set column names automatically
     * @var boolean
     */
    protected $isAutoColumnNames = false;

    /**
     * Status of database transaction
     * @var boolean
     */
    protected $isTransaction = true;

    /**
     * Status of resource collection or single
     * @var boolean
     */
    protected $isCollection = true;

    /**
     * Status of pagination
     * @var boolean
     */
    protected $isPaginate = true;

    /**
     * Hooks Actions and Filter
     * @var array
     */
    protected $hooks = array();

    /**
     * Form Input Filler
     * @var array
     */
    protected $formInputFiller = array();

    /**
     * Form group
     * @var array
     */
    protected $formGroup = array();

    /**
     * Form input
     * @var array
     */
    protected $formInput = array();

    /**
     * Form input view
     * @var array
     */
    protected $formInputView = array();

    /**
     * Table relationship
     * @var array
     */
    protected $relation = array();

    /**
     * Middleware start session
     *
     * @var \Illuminate\Session\Middleware\StartSession
     */
    protected $middlewareStartSession = null;

    /**
     * Visibility list elements
     *
     * @var array
     */
    protected $visibilityListElements = array(
        'create_button' => true,
        'submit_button' => true,
        'records_per_page' => true,
        'multi_search' => true,
        'single_search' => true,
        'pagination_info' => true,
        'pagination' => true,
    );

    /**
     * HTTP Verb
     * 
     * @var string
     */
    public $httpVerb;
    protected $searchIndexes = array();

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        // Set middleware start session
//        $this->middlewareStartSession= app('Illuminate\Session\Middleware\StartSession');
        $this->listOfRecordsPerPage = array(
            10 => 10,
            25 => 25,
            50 => 50,
            100 => 100,
            "all" => 'All',
        );
//        // Set Route, action name, and Request
//        $this->Route      = Route::current();
//        $this->Route      = ($this->Route) ? $this->Route : Route::getRoutes()->getRoutes()[0];
//        $this->actionName = "\\".$this->Route->getActionName();
//        // Set Controller
//        $this->controller = $this->getControllerOfActionName($this->actionName);
        // Populate parameters for javascript
        $this->jsParameters = array(
            'blockUIText' => "Loading...",
            'blockUI' => 1,
        );
    }

    /**
     * Set collection
     *
     * @param boolean $status
     * @return boolean
     */
    public function setCollection($status) {
        $this->isCollection = $status;
    }

    /**
     * Set collection
     *
     * @param boolean $status
     * @return boolean
     */
    public function setPaginateStatus($status) {
        $this->isPaginate = $status;
    }

    /**
     * Set Request
     *
     * @return void
     */
    public function setRequest($request) {
        $this->Request = $request;
    }

    /**
     * Set Response
     *
     * @return void
     */
    public function setResponse($response) {
        $this->Response = $response;
    }

    /**
     * Get Response
     *
     * @return void
     */
    public function getResponse() {
        return $this->Response;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return void
     */
    public function setTemplate($template) {
        $this->template = $template;
    }

    /**
     * Add Hooks Action and Filter
     *
     * @param string $hookName
     * @param string $callback
     */
    public function addHooks($hookName, $callback) {
        $this->hooks[$hookName][] = $callback;
    }

    /**
     * Call Hooks Action
     *
     * @param string $hookName
     * @param array $args
     * @return mixed
     */
    public function doHooks($hookName, $args = array()) {
        if (array_key_exists($hookName, $this->hooks)) {
            foreach ($this->hooks[$hookName] as $callback) {
                if (is_callable($callback, false)) {
                    call_user_func_array($callback, $args);
                }
            }
        }
    }

    /**
     * Call Hooks Filter
     *
     * @param string $hookName
     * @param array $args
     * @return mixed
     */
    public function doFilter($hookName, $args) {
        $result = $args;
        if (array_key_exists($hookName, $this->hooks)) {
            foreach ($this->hooks[$hookName] as $callback) {
                if (is_callable($callback, false)) {
                    $result = call_user_func($callback, $args);
                }
            }
        }
        return $result;
    }

    /**
     * Generate model of specific table
     *
     * @return void
     */
    public function getModelTable($tableName) {
        // Set initial configuration for Model
        $Model = new Model();
        return $Model->build($tableName, $this);
    }

    /**
     * Set table and initial configuration of Model, identifier and Model
     *
     * @return void
     */
    public function setTable($tableName, $aliasName = null) {
        $aliasName = ( $aliasName ) ? $aliasName : $tableName;
        // Set initial configuration for Model
        // Set table
        $this->table = $tableName;
        $Model = new Model();
        $Model = $Model->build($tableName, $this);
        if ($Model) {
            // Set Model
            $this->setModel($Model);
        }
        // Set identifier
        $this->setIdentifier($aliasName);
    }

    /**
     * Set load script for AJAX functionality or not
     *
     * @param boolean $status
     */
    public function setIsLoadScript($status) {
        $this->isLoadScript = $status;
    }

    /**
     * Set load required files for AJAX functionality or not
     *
     * @param boolean $status
     */
    public function setIsLoadRequiredFiles($status) {
        $this->isLoadRequiredFiles = $status;
    }

    /**
     * Set javascript parameters
     */
    public function setJavascriptParameters($parameters) {
        $this->jsParameters = array_merge($this->jsParameters, $parameters);
    }

    /**
     * Get javascript parameters
     */
    public function getJavascriptParameters() {
        return $this->jsParameters;
    }

    /**
     * Get controller
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * Get controller from action name
     *
     * @param string $hookName
     * @param string $hookFunction
     */
    public static function getControllerOfActionName($actionName) {
        $parameters = explode("@", $actionName);
        return "\\" . $parameters[0];
    }

    /**
     * Get controller from action name
     *
     * @param string $hookName
     * @param string $hookFunction
     */
    public static function getMethodOfActionName($actionName) {
        $parameters = explode("@", $actionName);
        return $parameters[1];
    }

    /**
     * Set type
     *
     * @param Illuminate\Database\Eloquent\Model $Model
     */
    public function setType($value) {
        $this->type = ( $value == "resource" ) ? $value : null;
    }

    /**
     * Get Model
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getModel() {
        return $this->Model;
    }

    /**
     * Set Model, table and default columns
     *
     * @param Illuminate\Database\Eloquent\Model $Model
     */
    public function setModel($Model) {
        // Set Model
        $this->Model = clone $Model;
        // Set default columns
        $this->setDefaultColumns();
    }

    /**
     * Set custom order by clause
     *
     * return void
     */
    public function orderBy($column, $orderType) {
        $this->order = $column;
        $this->orderType = $orderType;
    }

    /**
     * Set default columns
     *
     * return void
     */
    public function setDefaultColumns() {
        $this->defaultColumns = array();
        $this->columns = array();
        $this->indexes = array();
        foreach ($this->Model->columnsInformation as $column) {
            // Set indexes
            if ($column['Key'] == "PRI") {
                $this->indexes[] = $this->table . "." . $column['Field'];
            }
            // Set columns
            $this->columns[] = $this->table . "." . $column['Field'];
        }
        // Set default columns
        $this->defaultColumns = $this->columns;
//        // Add actions column
//        $this->addCustomColumn($this->builtInCustomColumns['actions']);
//        // Add no column
//        $this->addCustomColumnAsFirstColumn($this->builtInCustomColumns['no']);
    }

    /**
     * Get indexes
     */
    public function getIndexes() {
        return $this->indexes;
    }

    /**
     * Check column alias or not
     *
     * return void
     */
    public function isAlias($column) {
        $aliases = array_flip($this->aliases);
        return isset($aliases[$column]);
    }

    /**
     * Convert string to dot notation
     *
     * @param string $value camel or snake case string
     * @return string dot notation string
     */
    public function stringToDotNotation($value) {
        $dotString = "";
        if ($this->columnNameCase == self::SNAKECASE) {
            $dotString = str_replace("_", ".", $value);
        } else {
            $dotString = str_replace("_", ".", snake_case($value));
        }
        return $dotString;
    }

    /**
     * Get alias column
     *
     * @param string $column
     *
     * @return mixed string|null
     */
    public function getAliasColumn($column) {
        preg_match('/(?P<column>.+) AS (?P<alias>.+)/', $column, $matches);
        return isset($matches['alias']) ? $matches['alias'] : null;
    }

    /**
     * Set columns manually
     *
     * return void
     */
    public function setColumns($columns) {
        $this->columns = array();
        $this->aliases = array();
        $this->customColumns = array();
        foreach ($columns as $columnIndex => $column) {
            // Is custom Column
            if (array_key_exists($column, $this->builtInCustomColumns) ||
                    isset($this->columnProperties[$column]['callback'])) {
                if (array_key_exists($column, $this->builtInCustomColumns)) {
                    $column = $this->builtInCustomColumns[$column];
                } elseif (isset($this->columnProperties[$column]['callback'])) {
                    $column = $this->columnProperties[$column];
                    unset($column['width']);
                }
                $this->addCustomColumn($column);
            } else {
                $alias = $this->getAliasColumn($column);
                // Check column alias or not
                if ($alias) {
                    // Set alias columns
                    $this->aliases[$alias] = $column;
                    $columnIdentifier = $alias;
                } else {
                    // Add prefix entity or table name to column name ( support column name only )
                    $columnIdentifier = $this->addPrefixEntity($column);
                }
                $this->columns[] = $columnIdentifier;
            }
        }
    }

    /**
     * Set columns properties
     *
     * return void
     */
    public function setColumnProperties($parameters) {
        $columns = array();
        foreach ($parameters as $parameter) {
            $columns[] = $parameter['name'];
            $alias = $this->getAliasColumn($parameter['name']);
            $column = ( $alias ) ? $alias : $this->addPrefixEntity($parameter['name']);
            $this->columnProperties[$column] = $parameter;
        }
        $this->setColumns($columns);
    }

    /**
     * Get columns properties
     *
     * return array
     */
    public function getColumnProperties() {
        return $this->columnProperties;
    }

    /**
     * Get column name remove prefix entity or table name
     *
     * @param string $column
     * @return string
     */
    public function getColumnInfo($column) {
        $columnParams = explode(".", $column);
        return $columnParams[1];
    }

    /**
     * Set type to resource
     */
    public function setToResource() {
        $this->type = "resource";
    }

    /**
     * Set master template
     *
     * @param string $value
     */
    public function setMasterTemplate($value) {
        $this->masterTemplate = $value;
    }

    /**
     * Set column name for sequence number column
     *
     * @param callback $callback
     */
    public function setColumnNameSequenceNumber($callback) {
        foreach ($this->customColumns as $index => $columns) {
            if ($index == "x_no") {
                $columns['callbackColumn'] = $callback;
            }
            $this->customColumns[$index] = $columns;
        }
    }

    /**
     * Set sequence number column as first column in default custom columns
     */
    public function addSequenceNumberAsFirstColumn() {
        // Set custom columns as first column
        $this->addCustomColumnAsFirstColumn("No", array($this, 'getSequenceNumber'));
    }

    /**
     * Set sequence number column after specific column in default custom columns
     */
    public function addSequenceNumberAfterColumn($afterColumnName) {
        // Set custom columns as first column
        $this->addCustomColumnAfterColumn($afterColumnName, "No", array($this, 'getSequenceNumber'));
    }

    /**
     * Get first sequence number
     *
     * @return int
     */
    public function getFirstSequenceNumber() {
        return ($this->page - 1) * $this->getRecordsPerPage() + 1;
    }

    /**
     * Get sequence number
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     *
     * @return int
     */
    public function getSequenceNumber($record) {
        $this->sequenceNumber = ($this->sequenceNumber) ? $this->sequenceNumber + 1 :
                $this->getFirstSequenceNumber();
        echo $this->sequenceNumber;
    }

    /**
     * Define action buttons
     */
    public function setActionButtons($value) {
        $this->actionButtons = $value;
    }

    /**
     * Get action button urls
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     *
     * @return array
     */
    public function getCreateButtonUrl() {
        $uri = $this->Route->uri();
        $parameters = array(
            'action' => 'create',
        );
        $queryString = http_build_query($this->addingExtraParameters($parameters));
        $url = url($uri . "?" . $queryString);
        return $url;
    }

    /**
     * 
     */
    public function getUri() {
        return $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Get action button urls
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     *
     * @return array
     */
    public function getActionButtonUrls($record) {
        $indexes = array();
        foreach ($this->indexes as $index) {
            list($table, $column) = explode(".", $index);
            if ($this->type == "resource") {
                $indexes[] = $record[$column];
            } else {
                $indexes[$index] = $record[$column];
            }
        }
        // Set url for action buttons
        $uri = $this->getUri();
//header('content-type:text/plain');
//print_r($_SERVER);
//exit;
//        $uri= "";
        $parameters = array(
            'action' => 'view',
            'indexes' => $indexes,
        );
        $parameters = $this->addingExtraParameters($parameters);
        $queryString = http_build_query($parameters);
        $urlView = $uri . "?" . $queryString;
        $parameters['action'] = 'edit';
        $queryString = http_build_query($parameters);
        $urlEdit = $uri . "?" . $queryString;
        $parameters['action'] = 'delete';
        $queryString = http_build_query($parameters);
        $urlDelete = $uri . "?" . $queryString;
        return array(
            'view' => $urlView,
            'edit' => $urlEdit,
            'delete' => $urlDelete,
        );
    }

    /**
     * Get action buttons support for resource and any type
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     */
    public function getActionButtons($record) {
        $url = $this->getActionButtonUrls($record);
        // Display Buttons
        ?>
        <div class="text-center">
            <div class="btn-group">
                <?php
                $buttons = array('view', 'edit', 'delete');
                foreach ($buttons as $button) {
                    switch ($button) {
                        case "view":
                            ?>
                            <a href="<?php echo $url['view']; ?>" class="btn btn-primary btn-view">View</a>
                            <?php
                            break;
                        case "edit":
                            ?>
                            <a href="<?php echo $url['edit']; ?>" class="btn btn-primary btn-edit">Edit</a>
                            <?php
                            break;
                        case "delete":
                            ?>
                            <a href="<?php echo $url['delete']; ?>" class="btn btn-primary btn-remove">Remove</a>
                            <?php
                            break;
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Get list of records per page
     *
     * @param array $parameters
     *
     * @return array
     */
    public function getListOfRecordsPerPage() {
        return $this->listOfRecordsPerPage;
    }

    /**
     * Set list of records per page
     *
     * @param array $parameters
     */
    public function setListOfRecordsPerPage(array $parameters) {
        $this->listOfRecordsPerPage = $parameters;
    }

    /**
     * Set auto column names status
     *
     * @param boolean $status
     */
    public function setIsAutoColumnNames($status) {
        $this->isAutoColumnNames = $status;
    }

    /**
     * Get auto column names status
     *
     * @return boolean
     */
    public function getIsAutoColumnNames() {
        return $this->isAutoColumnNames;
    }

    /**
     * Set prefix translation
     *
     * @param string $status
     */
    public function setPrefixTranslation($status) {
        $this->prefixTranslation = $status;
    }

    /**
     * Get prefix translation
     *
     * @return string
     */
    public function getPrefixTranslation() {
        return $this->prefixTranslation;
    }

    function getResourceAction() {
        $routeName = $this->Route->getName();
        $routeName = explode(".", $routeName);
        return isset($routeName[1]) ? $routeName[1] : null;
    }

    /**
     * Routing HTTP Request according to the action parameter ( support route resource and any )
     */
    public function execute() {
        // HTTP Request Get
        if ($this->type == "resource") {
            $action = $this->getResourceAction();
            switch ($action) {
                case "show":
                    $this->show();
                    break;
                case "create":
                    $this->create();
                    break;
                case "edit":
                    $this->edit();
                    break;
                case "destroy":
                    $this->destroy();
                    break;
                default:
                    echo $this->render();
                    break;
            }
        } else {
            $action = $this->Request->action;
            switch ($action) {
                case "show":
                    $this->show();
                    break;
                case "create":
                    $this->create();
                    break;
                case "edit":
                    $this->edit();
                    break;
                case "destroy":
                    $this->destroy();
                    break;
                default:
                    echo $this->render();
                    break;
            }
        }
    }

    /**
     * Get default records per page
     *
     * @return int
     */
    public function getDefaultRecordsPerPage() {
        return key($this->listOfRecordsPerPage);
    }

    /**
     * Get Request
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest() {
        return $this->Request;
    }

    /**
     * Get records per page
     *
     * @return int
     */
    public function getRecordsPerPage() {
        return ( $this->Request->getQueryParam('recordsPerPage') ) ? $this->Request->getQueryParam('recordsPerPage') : $this->getDefaultRecordsPerPage();
    }

    /**
     * Get columns
     *
     * @return array
     */
    public function getColumns() {
        $columns = array();
        foreach ($this->columns as $column) {
            if (!array_key_exists($column, $this->customColumns)) {
                $columns[] = $column;
            }
        }
        return $columns;
    }

    /**
     * Get columns for select statement
     *
     * @return array
     */
    public function getSelectStatementColumns() {
        $columns = array();
        foreach ($this->columns as $column) {
            if (!array_key_exists($column, $this->customColumns)) {
                // Support alias column
                $column = array_key_exists($column, $this->aliases) ? DB::raw($this->aliases[$column]) : $column;
                $columns[] = $column;
            }
        }
//        // Add index into columns for situation index is not exist
//        foreach ($this->indexes as $index) {
//            if (! in_array($index, $columns)) {
//                array_unshift($columns, $index);
//            }
//        }
        if ($columns[0] instanceof \Illuminate\Database\Query\Expression) {
            $columns[0] = DB::raw("SQL_CALC_FOUND_ROWS " . $columns[0]->getValue());
        } else {
            $columns[0] = DB::raw("SQL_CALC_FOUND_ROWS " . $columns[0]);
        }
        return $columns;
    }

    /**
     * Adding extra parameters
     *
     * @param array $parameters
     * @return array
     */
    public function addingExtraParameters($parameters) {
        $parameters['identifier'] = $this->identifier;
        return $parameters;
    }

    /**
     * Check identifier is valid
     *
     * @return boolean
     */
    public function isIdentifierValid() {
        return ($this->Request->getQueryParam('identifier') == $this->identifier) ? true : false;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * Set identifier
     */
    protected function setIdentifier($value) {
        $this->identifier = $value;
    }

    /**
     * Get visibility list elements
     *
     * @return array
     */
    public function getVisibilityListElements() {
        return $this->visibilityListElements;
    }

    /**
     * Set Search Indexes
     * 
     * @param string $column
     * @return \Scaffolding\Scaffolding
     */
    public function setSearchIndexes($column, $request_name = "") {
        if (is_array($column)) {
            $this->searchIndexes = $column;
        } else {
            $this->searchIndexes[$column] = $request_name;
        }
        return $this;
    }

    /**
     * Get search indexes
     *
     * @return array
     */
    public function getSearchIndexes() {
        return $this->searchIndexes;
    }

    /**
     * Set visibility list elements
     *
     * @param array $parameters
     *
     * @return void
     */
    public function setVisibilityListElements($parameters) {
        $this->visibilityListElements = array_merge($this->visibilityListElements, $parameters);
    }

    /**
     * Get status of database transaction
     *
     * @return array
     */
    public function getIsTransaction() {
        return $this->isTransaction;
    }

    /**
     * Set status of database transaction
     *
     * @return array
     */
    public function setIsTransaction($status) {
        $this->isTransaction = $status;
    }

    /**
     * Get form action
     *
     * @return string
     */
    public function getFormAction() {
        return action($this->actionName, Route::current()->parameters());
    }

    /**
     * Get form layout
     *
     * @return array
     */
    public function getFormLayout() {
        $layout = $this->Model->layout;
        // Hook Filter modifyLayout
        $layout = $this->doFilter("modifyLayout", $layout);
        return $layout;
    }

    /**
     * Get form columns
     *
     * @return array
     */
    public function getFormColumns() {
        $columns = $this->Model->columns;
        // Hook Filter modifyColumnsProperties
        $columns = $this->doFilter("modifyColumnsProperties", $columns);
        return $columns;
    }

    /**
     * Get form input indexes
     *
     * @return string
     */
    public function getFormInputIndexes() {
        $indexes = $this->getIndexes();
        ob_start();
        foreach ($indexes as $index) {
            list($tableName, $columnName) = explode(".", $index);
            $column = $this->Model->columns[$columnName];
            echo Form::hidden($columnName, null, $column['attributes']);
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Get form label view
     *
     * @return string
     */
    public function getFormLabelView($column) {
        ob_start();
        ?>
        <div class="form-label"><?php echo $column['label']; ?></div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Get form label
     *
     * @return string
     */
    public function getFormLabel($column) {
        ob_start();
        $extraHtml = ($column['require']) ? ' <span class="required">*</span>' : '';
        ?>
        <label for="<?php echo $column['name']; ?>"><?php echo $column['label'] . $extraHtml; ?></label>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Get form label checkbox
     *
     * @return string
     */
    public function getFormLabelCheckbox($column) {
        ob_start();
        $extraHtml = ($column['require']) ? ' <span class="required">*</span>' : '';
        echo $column['label'] . $extraHtml;
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Set form input filler
     *
     * @return $this
     */
    public function setFormInputFiller($column, $values) {
        $this->formInputFiller[$column] = $values;
        return $this;
    }

    /**
     * Get form input filler
     *
     * @return array
     */
    public function getFormInputFiller() {
        return $this->formInputFiller;
    }

    /**
     * Set form group
     *
     * @return $this
     */
    public function setFormGroup($column, $callback) {
        $this->formGroup[$column] = $callback;
        return $this;
    }

    /**
     * Check form group for specific column is valid
     *
     * @return string
     */
    public function hasFormGroup($column) {
        $columnName = $column['name'];
        return ( isset($this->formGroup[$columnName]) ) ? true : false;
    }

    /**
     * Get form group
     *
     * @return string
     */
    public function getFormGroup($column) {
        $columnName = $column['name'];
        $column['attributes']['id'] = $column['name'];
        ob_start();
        call_user_func($this->formGroup[$columnName], $column, $this);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Set form input
     *
     * @return $this
     */
    public function setFormInput($column, $callback) {
        $this->formInput[$column] = $callback;
        return $this;
    }

    /**
     * Get form input
     *
     * @return string
     */
    public function getFormInput($column) {
        ob_start();
        $Model = clone $this->getModel();
        $columnsInformation = $Model->columnsInformation;
        $columnName = $column['name'];
        $column['attributes']['id'] = $column['name'];
        if (isset($this->formInput[$columnName])) {
            call_user_func($this->formInput[$columnName], $column, $this);
        } else {
            $inputType = $column['type'];
            switch ($inputType) {
                case "password":
                    echo Form::password($column['name'], $column['attributes']);
                    break;
                case "checkbox":
                    echo Form::checkbox($column['name'], 1, null, $column['attributes']);
                    break;
                case "select":
                    $values = isset($this->formInputFiller[$column['name']]) ?
                            $this->formInputFiller[$column['name']] : array();
                    echo Form::select($column['name'], $values, null, $column['attributes']);
                    break;
                default:
                    if ($column['type'] == "file" &&
                            $columnsInformation[$column['name']]['Null'] == 'YES' &&
                            $Model[$column['name']]) {
                        ?>
                        <div class="checkbox">
                            <?php echo Form::$inputType($column['name'], null, $column['attributes']); ?>
                            <label>
                                <?php echo Form::checkbox($column['name'] . "_remove", 1) . trans('dkscaffolding.remove'); ?>
                            </label>
                        </div>
                        <?php
                    } else {
                        echo Form::$inputType($column['name'], null, $column['attributes']);
                    }
                    break;
            }
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Set form input view
     *
     * @return $this
     */
    public function setFormInputView($column, $callback) {
        $this->formInputView[$column] = $callback;
        return $this;
    }

    /**
     * Get form input view
     *
     * @return string
     */
    public function getFormInputView($column) {
        ob_start();
        $columnName = $column['name'];
        if (isset($this->formInputView[$columnName])) {
            call_user_func($this->formInputView[$columnName], $column, $this->Model, $this);
        } else {
            $inputType = $column['type'];
            $value = $this->Model[$columnName];
            switch ($inputType) {
                case "select":
                    $value = isset($this->formInputFiller[$columnName][$value]) ?
                            $this->formInputFiller[$columnName][$value] : $value;
                default:
                    ?>
                    <div class="form-content"><?php echo $value; ?></div>
                    <?php
                    break;
            }
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Get form input error
     *
     * @return string
     */
    public function getFormInputError($column) {
        ob_start();
        $errors = request()->session()->get('errors');
        if ($errors) {
            $errorMessages = $errors->getMessages();
            $labelError = '<label class="error">%1$s</label>';
            // Hook Filter modifyLabelFormInputError
            $labelError = $this->doFilter("modifyLabelFormInputError", $labelError);
            // Display errors
            if (isset($errorMessages[$column])) {
                foreach ($errorMessages[$column] as $errorMessage) {
                    echo sprintf($labelError, e($errorMessage));
                }
            }
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Process DELETE - Delete specific row or record
     *
     * @return string
     */
    public function processDelete() {
        // Hook Filter isValidRecord
        $Model = $this->doFilter("isValidRecord", clone $this->Model);
        $Model = $Model->select($this->table . '.*')->first();
        // Is valid record
        if (!$Model) {
            // Redirect to List Page
            // Set session flash for notify Entry is not exist
            $Response = clone $this->Response;
            $parameters = array(
                'status_code' => 200,
                'message' => 'Record is not valid',
            );
            return $Response->withRedirect($_SERVER['REQUEST_URI'], 200)->withJson($parameters);
        }
        // Set initial configuration
        $Model->build($this->Model->getTable(), $this);
        // Delete record
        if ($this->getIsTransaction()) {
            $result = DB::transaction(function ($db) use ($Model) {
                        $result = $Model->delete();
                        // Hook Action deleteAfterDelete
                        $this->doHooks("deleteAfterDelete", array($Model));
                    });
        } else {
            $result = $Model->delete();
            // Hook Action deleteAfterDelete
            $this->doHooks("deleteAfterDelete", array($Model));
        }
        $this->Model = $Model;
        // Set response redirect to list page and set session flash
        $Response = clone $this->Response;
        $Response = $Response->withJson([
            'status_code' => 200,
            'data' => $Model->toArray(),
        ]);
        // Hook Filter deleteModifyResponse
        $Response = $this->doFilter("deleteModifyResponse", $Response);
        return $Response;
    }

    /**
     * Render delete specific record
     *
     * @return string
     */
    public function renderDelete() {
        // Is valid record
        $result = $this->prepareRecord();
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            $this->redirect($result);
        }
        // Render View
        $parameters = array(
            "Scaffolding" => $this,
        );
        $content = view($this->template . ".delete", $parameters)->render();
        return $content;
    }

    /**
     * Render view specific record
     *
     * @return string
     */
    public function renderView() {
        // Is valid record
        $result = $this->prepareRecord();
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            $this->redirect($result);
        }
        // Render View
        $parameters = array(
            "Scaffolding" => $this,
        );
        $content = view($this->template . ".view", $parameters)->render();
        return $content;
    }

    /**
     * Prepare record for action
     *
     * @return \App\Libraries\Scaffolding\Model
     */
    public function setWhereIndexes($Model) {
        // Get specific record
        $requestParameters = $this->Request->all();
        foreach ($this->indexes as $column) {
            list($tableName, $columnName) = explode(".", $column);
            $Model = $Model->where($columnName, '=', $requestParameters[$columnName]);
        }
        return $Model;
    }

    /**
     * Prepare record for action
     *
     * @return \App\Libraries\Scaffolding\Model
     */
    public function isValidRecord() {
        // Get specific record
        $Model = $this->setWhereIndexes($this->Model);
        $record = $Model->first();
//        // Is valid record
//        if (!$record) {
//            // Redirect to List Page
//            // Set session flash for notify Entry is not exist
//            return redirect($this->getFormAction())
//            ->with('dk_'.$this->getIdentifier().'_info_error', trans('dkscaffolding.no.entry'));
//        }
        return $record;
    }

    /**
     * Get specific record and check record is valid
     *
     * @return string
     */
    public function prepareRecord() {
        // Get specific record
        $requestParameters = $this->Request->all();
        $Model = clone $this->Model;
        foreach ($requestParameters['indexes'] as $column => $value) {
            $Model = $Model->where($column, '=', $value);
        }
        $record = $Model->first();
        // Is valid record
        if (!$record) {
            // Redirect to List Page
            // Set session flash for notify Entry is not exist
            return redirect($this->getFormAction())
                            ->with('dk_' . $this->getIdentifier() . '_info_error', trans('dkscaffolding.no.entry'));
        }
        $this->Model->setRawAttributes($record->getAttributes(), true);
    }

    /**
     * Render edit form
     *
     * @return string
     */
    public function renderEdit() {
        $result = $this->prepareRecord();
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            $this->redirect($result);
        }
        // Render View
        $parameters = array(
            "Scaffolding" => $this,
        );
        $content = view($this->template . ".edit", $parameters)->render();
        return $content;
    }

    /**
     * Get validation rules
     *
     * @return array
     */
    public function getValidationRules() {
        $rules = $this->Model->validationRules;
        return $rules;
    }

    /**
     * Set validation rules
     *
     * @return $this
     */
    public function setValidationRules($rules) {
        $this->Model->validationRules = $rules;
        return $this;
    }

    /**
     * Get validation rules javascript
     *
     * @return array
     */
    public function getValidationRulesJs() {
        $rules = $this->Model->validationRulesJS;
        // Hook Filter modifyValidationRulesJS
        $rules = $this->doFilter("modifyValidationRulesJS", $rules);
        return $rules;
    }

    /**
     * Set validation rules javascript
     *
     * @return $this
     */
    public function setValidationRulesJs($rules) {
        $this->Model->validationRulesJS = $rules;
        return $this;
    }

    /**
     * Set value for BIT columns
     *
     * @return void
     */
    public function setValueBitColumns() {
        // Get BIT columns
        $columns = $this->Model->bitColumns;
        foreach ($columns as $column) {
            $this->Request[$column] = (isset($this->Request[$column])) ? 1 : 0;
        }
    }

    /**
     * Set value for BLOB columns
     *
     * @return void
     */
    public function setValueBlobColumns($parameters) {
        // Get BLOB columns
        $columns = $this->Model->blobColumns;
        foreach ($columns as $column) {
            if (isset($parameters[$column])) {
                $parameters[$column] = file_get_contents($parameters[$column]->getRealPath());
            } elseif (isset($parameters[$column . '_remove'])) {
                $parameters[$column] = null;
            }
        }
        return $parameters;
    }

    /**
     * Redirect
     *
     * @param \Illuminate\Http\RedirectResponse $Response
     *
     * @return string
     */
    protected function redirect($Response) {
        $this->middlewareStartSession->terminate(request(), $Response);
        $Response->send();
        exit;
    }

    /**
     * Process PUT - Update specific row or record
     *
     * @return string
     */
    public function processUpdate() {
        // Hook Filter updateBeforeUpdate
        $parameters = array(
            'me' => $this,
        );
        $result = $this->doFilter("updateBeforeUpdate", $parameters);
        if ($result instanceof \Slim\Http\Response) {
            return $result;
        }        
        // Record is valid
//        $Model= $this->isValidRecord();
        // Hook Filter isValidRecord
        $Model = $this->doFilter("isValidRecord", $this->Model);
        $Model = $Model->select($this->table . '.*')->first();
        // Is valid record
        if (!$Model) {
            // Redirect to List Page
            // Set session flash for notify Entry is not exist
            $Response = clone $this->Response;
            $parameters = array(
                'status_code' => 200,
                'message' => 'Record is not valid',
            );
            return $Response->withRedirect($_SERVER['REQUEST_URI'], 200)->withJson($parameters);
        }
        // Set initial configuration
        $Model->build($this->table, $this);
        $this->Model = $Model;
        // Get request parameters
        $requestParameters = $this->Request->getParams();
        // Hook Filter updateModifyRequest
        $requestParameters = $this->doFilter("updateModifyRequest", $requestParameters);
        // Validate Request
        $validationRules = $this->getValidationRules();
        // Hook Filter updateModifyValidationRules
        $validationRules = $this->doFilter("updateModifyValidationRules", $validationRules);
        // Validate Request
        $v = new Validator($requestParameters);
        foreach ($validationRules as $rule => $columns) {
            switch ($rule) {
                case "dateFormat":
                case "lengthMax":
                    foreach ($columns as $parameters) {
                        list($column, $format) = $parameters;
                        $v->rule($rule, $column, $format);
                    }
                    break;
                default:
                    $v->rule($rule, $columns);
            }
        }
//        if (!$v->validate()) {
        if (FALSE) {
            $parameters = array(
                'status_code' => 400,
                'message' => 'Validation error.',
                'errors' => $v->errors(),
            );
            $Response = clone $this->Response;
            return $Response->withStatus(400)->withJson($parameters);
        }
        // Update record
        if ($this->getIsTransaction()) {
            DB::transaction(function ($db) use ($Model, $requestParameters) {
                $Model->update($requestParameters);
                // Hook Action updateAfterUpdate
                $this->doHooks("updateAfterUpdate", array($Model));
            });
        } else {
            $Model->update($requestParameters);
            // Hook Action updateAfterUpdate
            $this->doHooks("updateAfterUpdate", array($Model));
        }
        // Set response - redirect to list page
        $Response = clone $this->Response;
        $Response = $Response->withJson([
            'status_code' => 200,
            'data' => $Model->toArray(),
        ]);
        // Hook Filter updateModifyResponse
        $Response = $this->doFilter("updateModifyResponse", $Response);
        return $Response;
    }

    /**
     * Process POST - Insert new row or record
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processInsert() {
        // Hook Filter insertBeforeInsert
        $parameters = array(
            'me' => $this,
        );
        $result = $this->doFilter("insertBeforeInsert", $parameters);
        if ($result instanceof \Slim\Http\Response) {
            return $result;
        }
        // Get request parameters
        $requestParameters = $this->Request->getParams();
        // Hook Filter insertModifyRequest
        $requestParameters = $this->doFilter("insertModifyRequest", $requestParameters);
        // Get validation rules
        $validationRules = $this->getValidationRules();
        // Hook Filter insertModifyValidationRules
        $validationRules = $this->doFilter("insertModifyValidationRules", $validationRules);
        $validationMessages = array();
        // Hook Filter insertModifyValidationMessages
        $validationMessages = $this->doFilter("insertModifyValidationMessages", $validationMessages);
        // Validate Request
        $v = new Validator($requestParameters);
        foreach ($validationRules as $rule => $columns) {
            switch ($rule) {
                case "dateFormat":
                case "lengthMax":
                    foreach ($columns as $parameters) {
                        list($column, $format) = $parameters;
                        $v->rule($rule, $column, $format);
                    }
                    break;
                default:
                    $v->rule($rule, $columns);
            }
        }
//        if (!$v->validate()) {
        if (FALSE) {
            $parameters = array(
                'status_code' => 400,
                'message' => 'Validation error.',
                'errors' => $v->errors(),
            );
            $Response = clone $this->Response;
            return $Response->withStatus(400)->withJson($parameters);
        }
        // Insert record
        if ($this->getIsTransaction()) {
            DB::transaction(function ($db) use ($requestParameters) {
                $result = $this->Model->fill($requestParameters)->save();
                // Hook Action insertAfterInsert
                $this->doHooks("insertAfterInsert", array($this->Model));
            });
        } else {
            $result = $this->Model->fill($requestParameters)->save();
            // Hook Action insertAfterInsert
            $this->doHooks("insertAfterInsert", array($this->Model));
        }
        // Set response - redirect to list page
        $Response = clone $this->Response;
        $Response = $Response->withStatus(201)->withJson([
            'status_code' => 201,
            'data' => $this->Model->toArray(),
        ]);
        // Hook Filter insertModifyResponse
        $Response = $this->doFilter("insertModifyResponse", $Response);
        return $Response;
    }

    /**
     * Render create form
     *
     * @return string
     */
    public function renderCreate() {
        // Populate parameters for template file or View
        $parameters = array(
            "Scaffolding" => $this,
        );
        $content = view($this->template . ".create", $parameters)->render();
        return $content;
    }

    /**
     * Render View
     *
     * @return string
     */
    public function render() {
        $Request = $this->Request;
        // Hooks Filter httpVerb
        $this->httpVerb = $Request->getMethod();
        $this->httpVerb = $this->doFilter("httpVerb", $this->httpVerb);
        // Is List Page
        $content = null;
        if ($this->httpVerb == "GET" && !$Request->getQueryParam('action')) {
            // Check identifier is valid
            if (!$this->isIdentifierValid()) {
                if ($Request->isXhr()) {
                    return null;
                }
//                $Request->setQueryParam('page', 1);
//                $Request->initialize(array('page'=>1));
            }
            $content = $this->renderList();
        } else {
//            if ($this->isIdentifierValid()) {
            // Navigate request
            switch ($this->httpVerb) {
                case "POST":
                    $content = $this->processInsert();
                    break;
                case "PUT":
                case "PATCH":
                    $content = $this->processUpdate();
                    break;
                case "DELETE":
                    $content = $this->processDelete();
                    break;
                case "GET":
                    switch ($Request->action) {
                        case "create":
                            $content = $this->renderCreate();
                            break;
                        case "edit":
                            $content = $this->renderEdit();
                            break;
                        case "delete":
                            $content = $this->renderDelete();
                            break;
                        case "view":
                            $content = $this->renderView();
                            break;
                    }
                    break;
            }
//            }
        }
        return $content;
    }

    /**
     * Get select statement columns raw
     *
     * @return string
     */
    public function getSelectStatementColumnsString($columns) {
        $text = "";
        $ct = 1;
        foreach ($columns as $column) {
            $text.= $column;
            if (count($columns) != $ct) {
                $text.= ", ";
            }
            $ct++;
        }
        return $text;
    }

    /**
     * Render list
     *
     * @return string
     */
    public function renderList() {
        // Hook Filter listStart
        $result = $this->doFilter("listStart", $this);
        if ($result instanceof \Slim\Http\Response) {
            return $result;
        }
        $Request = $this->Request;
        // Set columns manually
        // Start Add columns parameter to SQL Query
        $columns = $this->getColumns();
        $selectColumns = $this->getSelectStatementColumns();
        $Model = clone $this->Model;
        // Hooks Filter listModifyColumns
        $selectColumns = $this->doFilter("listModifyColumns", $selectColumns);
        $Model = $Model->selectRaw($this->getSelectStatementColumnsString($selectColumns));
//        $Model               = $Model->select($selectColumns);
        // Define relationship between tables
        foreach ($this->relation as $relation) {
            list($sourceTable, $sourceColumn, $operator, $destinationColumn, $joinType) = $relation;
            $Model = $Model->join(
                    $sourceTable, $sourceColumn, $operator, $destinationColumn, $joinType
            );
        }
        // Hook Filter listModifyModel
        $Model = $this->doFilter("listModifyModel", clone $Model);
        // End Add columns parameter to SQL Query
        // Set custom columns
        // Add custom columns to columns
        // Set column names
        // Start Define column names
        // End Define column names
        // Define WHERE clause
        $searchParameters = $Request->getQueryParam('search');
        if ($searchParameters) {
            if (isset($searchParameters['multiple']) && $searchParameters['multiple']) {
                $value = $searchParameters['multiple'];
                // Filter multiple column
                foreach ($columns as $column) {
                    // Is alias column
                    if (array_key_exists($column, $this->aliases)) {
                        $Model = $Model->orHaving($column, 'LIKE', '%' . $value . '%');
                    } else {
                        $Model = $Model->orWhere($column, 'LIKE', '%' . $value . '%');
                    }
                }
            }
            unset($searchParameters['multiple']);
            // Filter individual column
            foreach ($searchParameters as $column => $value) {
                // Value is valid
                if ($value) {
                    // Is alias column
                    if (array_key_exists($column, $this->aliases)) {
                        $Model = $Model->having($column, 'LIKE', '%' . $value . '%');
                    } else {
                        $Model = $Model->where($column, 'LIKE', '%' . $value . '%');
                    }
                }
            }
        }
        // Filter column
        $searchIndexes = $this->getSearchIndexes('search');
        foreach ($searchIndexes as $column => $request_name) {
            $value = $Request->getParam($request_name);
            // Value is valid
            if ($value) {
                $Model = $Model->where($column, 'LIKE', '%' . $value . '%');
            }
        }
        // Add group by clause
        $groupBy = $this->getGroupBy();
        if ($groupBy) {
            $Model = $Model->groupBy($groupBy);
        }
        // Hook Filter listModifySearch
        $Model = $this->doFilter("listModifySearch", clone $Model);
        // Define order clause
        $this->order = ( $Request->getQueryParam('order') ) ? $Request->getQueryParam('order') : $this->getDefaultOrder();
        $this->orderType = ( $Request->getQueryParam('orderType') ) ? $Request->getQueryParam('orderType') : $this->orderType;
        $Model = $Model->orderBy($this->order, $this->orderType);
        // Define pagination
        // Page Parameter is valid
        // Set default page
//        $page= isset($Request->getQueryParam('page')) ? $Request->getQueryParam('page') : 1;
        $page = (int) $Request->getQueryParam('page');
        $this->page = ($page < 1) ? 1 : $page;
        $isPageParameterExist = $Request->getQueryParam('page') ? TRUE : FALSE;
        $status = true;
//        echo $Model->toSql();
//        exit;
        while ($status) {
            // Get records
            $per_page = $this->getRecordsPerPage();
            if ($this->isCollection && $this->isPaginate && $isPageParameterExist) {
                $offset = $this->page - 1;
                $Model->take($per_page)->offset($per_page * $offset);
            }
//            header('content-type: text/plain');
//            echo $Model->toSql();
//            exit;
            $this->records = $Model->get();
//            $records           = $Model->get();
//            print_r($this->records->toArray());
//            exit;
//            $this->records           = $Model->paginate($this->getRecordsPerPage(), array('*'), 'page', $this->page);
            $requestParameters = $this->addingExtraParameters($Request->getQueryParams());
//            $this->records->appends($requestParameters);
            // Get row count of SQL_CALC_FOUND_ROWS
            $foundRowsModel = clone $this->Model;
            $sql = 'SELECT FOUND_ROWS() AS total';
            $resultFoundRows = $foundRowsModel->runQuery($sql)->toArray();
            // Page is valid
            $total = $resultFoundRows[0]['total'];
            $lastPage = ceil($total / $per_page);
            $currentPage = $this->page;
            if ($currentPage > $lastPage && $total) {
                $this->page = $lastPage;
            } else {
                $status = false;
            }
        }
        // Set order columns
        foreach ($columns as $column) {
            if ($this->order == $column) {
                $columnOrder = ($this->orderType == "ASC") ? "DESC" : "ASC";
                $active = true;
            } else {
                $columnOrder = "ASC";
                $active = false;
            }
//            $requestParameters              = $Request->except(array('order', 'orderType'));
            $requestParameters = $Request->getQueryParams();
            $requestParameters['order'] = $column;
            $requestParameters['orderType'] = $columnOrder;
            $requestParameters = $this->addingExtraParameters($requestParameters);
//            $url                            = action($this->actionName, Route::current()->parameters() + $requestParameters);
            $url = "";
            $record = array();
            $record['url'] = $url;
            $record['orderType'] = $columnOrder;
            $record['active'] = $active;
            $this->orders[$column] = $record;
        }
        // Set formatter columns
        // Define formatter for columns
        // Set sortable columns
        // Define sortable columns
        // Set search visibility
        // Define search visibility
        // Set buttons visibility
        // Define buttons visibility
        // Set title
        // Define title
        // Populate parameters for template file or View
        $parameters = array(
            "Scaffolding" => $this,
        );
        $records = $this->getListRecords();
        if (!$this->isCollection) {
            $records = $records[0];
            // Hook Filter listModifyRecord
            $records['query_record'] = $this->records->first();
            $records = $this->doFilter("listModifyRecord", $records);
            unset($records['query_record']);
        }
        $isRecordsNotEmpty = TRUE;
        if (!$records) {
            $records = "There's no records";
            $isRecordsNotEmpty = FALSE;
        }
        // Populate parameters
        $parameters = array(
            'status_code' => 200,
            'data' => $records,
//                'records' => $records,
//                'pagination' => $this->getPagination(),
//                'paginationInfo' => $this->getPaginationInfo(),
//                'orders' => $this->getColumnsOrder(),
//                'columnProperties' => $this->getColumnProperties(),
//                'queries' => DB::getQueryLog(),
        );
        if ($this->isCollection && $this->isPaginate && $isPageParameterExist && $isRecordsNotEmpty) {
            $parameters['last_page'] = $lastPage;
            $parameters['current_page'] = $this->page;
        }
        $parameters = $this->doFilter("listModifyResponseParameters", $parameters);
        $this->records = $records;
        $Response = clone $this->Response;
        if ($this->Request->isXhr()) {
            // Send Response
            $Response = $Response->withHeader('Cache-Control', 'max-age=604800, public')
                    ->withJson($parameters);
        } else {
            // Send Response
            $Response = $Response->withHeader('Cache-Control', 'max-age=604800, public')
                    ->withJson($parameters);
//            // Render template file ( table support search, order and pagination )
//            $content = view($this->template.".index", $parameters)->render();
//            return $content;
        }
        $Response = $this->doFilter("listModifyResponse", $Response);
        return $Response;
    }

    /**
     * Get default order information
     *
     * @return array
     */
    public function getDefaultOrderInfo() {
        return array(
            'column' => $this->getDefaultOrder(),
            'sort' => $this->orderType,
        );
    }

    /**
     * Get default order
     *
     * @return string
     */
    public function getDefaultOrder() {
        if ($this->order) {
            return $this->order;
        }
        $defaultColumn = $this->defaultColumns[0];
        foreach ($this->columns as $column) {
            if (!array_key_exists($column, $this->customColumns)) {
                $defaultColumn = $column;
                break;
            }
        }
        return $defaultColumn;
    }

    /**
     * Get columns order
     *
     * @return string
     */
    public function getColumnsOrder() {
        $columnsOrder = $this->getColumnProperties();
        foreach ($columnsOrder as $column => $properties) {
            $orderActive = (isset($properties['order']) && $properties['order'] == FALSE) ? FALSE : TRUE;
            if (!$orderActive) {
                unset($this->orders[$column]);
            }
        }
        return $this->orders;
    }

    /**
     * Get pagination
     *
     * @return string
     */
    public function getPagination() {
        ob_start();
        echo $this->records->links();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Get column name
     *
     * @return array
     */
    protected function getColumnName($column) {
        return mb_convert_case(str_replace("_", " ", $column), MB_CASE_TITLE, "UTF-8");
    }

    /**
     * Process search columns
     *
     * @return array
     */
    public function getListSearchColumns() {
        $result = array();
        $columns = $this->columns;
        $customColumns = $this->customColumns;
        foreach ($columns as $column) {
            if (isset($customColumns[$column])) {
                $result[$column] = e("&nbsp;");
            } else {
                $value = isset($this->Request['search'][$column]) ? $this->Request['search'][$column] : null;

                $result[$column] = Form::text(
                                'search[' . $column . ']', $value, array('class' => 'form-control', 'placeholder' => trans('dkscaffolding.search.placeholder'))
                );
            }
        }
        return $result;
    }

    /**
     * Process columns
     *
     * @return array
     */
    public function getListColumns() {
        $result = array();
        foreach ($this->columns as $column) {
            if (isset($this->customColumns[$column])) {
                $params = $this->customColumns[$column];
                if (isset($params['callbackColumn'])) {
                    ob_start();
                    call_user_func($params['callbackColumn']);
                    $output = ob_get_contents();
                    ob_end_clean();
                    $result[$column] = $output;
                } else {
                    $result[$column] = e($params['label']);
                }
            } else {
                // Is alias column
                if (array_key_exists($column, $this->aliases)) {
                    $columnName = $column;
                } else {
                    list($tableName, $columnName) = explode(".", $column);
                }
                $columnName = isset($this->columnProperties[$column]['label']) ?
                        $this->columnProperties[$column]['label'] : $this->getColumnName($columnName);
                $result[$column] = e($columnName);
            }
        }
        return $result;
    }

    /**
     * Process column of every record
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $records
     * @return array
     */
    public function getListRecords() {
        $result = array();
        $records = $this->records;
        if (count($records)) {
            $columns = $this->columns;
            $customColumns = $this->customColumns;
            $formatterColumns = $this->formatterColumns;
            // Hook Filter listModifyCustomColumns
            $columns = $this->doFilter("listModifyColumnsRecords", $columns);
            foreach ($records as $index => $record) {
                $row = array();
                foreach ($columns as $column) {
                    if (isset($customColumns[$column])) {
                        $params = $customColumns[$column];
                        ob_start();
                        call_user_func($params['callback'], $record, $this);
                        $output = ob_get_contents();
                        ob_end_clean();
                        $row[$column] = $output;
                    } else {
                        if (isset($formatterColumns[$column])) {
                            $params = $formatterColumns[$column];
                            ob_start();
                            call_user_func($params['callback'], $record);
                            $output = ob_get_contents();
                            ob_end_clean();
                            // Is alias column
                            if (array_key_exists($column, $this->aliases)) {
                                $columnName = $column;
                            } else {
                                list($tableName, $columnName) = explode(".", $column);
                            }
                            $columnIdentifier = isset($columnName) ? $columnName : $column;
                            $row[$columnIdentifier] = $output;
                        } else {
                            // Is alias column
                            if (array_key_exists($column, $this->aliases)) {
                                $columnName = $column;
                            } else {
                                $regexSeparator = '/\./';
                                if (preg_match($regexSeparator, $column)) {
                                    list($tableName, $columnName) = explode(".", $column);
                                } else {
                                    $columnName = $column;
                                }
                            }
                            $columnIdentifier = isset($columnName) ? $columnName : $column;
//                            $row[$column] = $record[$columnIdentifier];
                            $row[$columnIdentifier] = isset($record[$columnIdentifier]) ? $record[$columnIdentifier] : null;
                        }
                    }
                }
                // Hook Filter listModifyRecords
                $row['query_record'] = $record;
                $row = $this->doFilter("listModifyRecords", $row);
                unset($row['query_record']);
                $result[$index] = $row;
            }
        }
        // Hook Filter listExtraRecords
        $result = $this->doFilter("listExtraRecords", $result);
        return $result;
    }

    /**
     * Get pagination info
     *
     * @param type $records
     */
    public function getPaginationInfo() {
        ob_start();
        if ($this->paginationInfoVisibility && !$this->records->isEmpty()) {
            $total = $this->records->total();
            $numberOfItem = count($this->records);
            $lastNumberPreviousPage = ($this->page - 1) * $this->getRecordsPerPage();
            $start = $lastNumberPreviousPage + 1;
            $last = $lastNumberPreviousPage + $numberOfItem;
            ?>
            <div class="pagination-info">
                <?php echo sprintf(trans('dkscaffolding.pagination.info'), $start, $last, $total); ?>
            </div>
            <?php
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public static function snakeCaseToTitle($value) {
        return mb_convert_case(str_replace("_", " ", $value), MB_CASE_TITLE, "UTF-8");
    }

    /**
     * Add prefix entity or table name to column name ( support column name only )
     *
     * @return Illuminate\View\View
     */
    public function addPrefixEntity($column) {
        $columnId = $this->table . "." . $column;
        if (in_array($columnId, $this->defaultColumns)) {
            return $columnId;
        }
        return $column;
    }

    public function getCustomColumnName($columnName) {
        return "x_" . mb_convert_case($columnName, MB_CASE_LOWER, "UTF-8");
    }

    /**
     * Remove custom columns
     *
     * @param mixed $columnName
     */
    public function removeCustomColumn($columnName) {
        if (is_array($columnName)) {
            $columns = array();
            foreach ($columnName as $name) {
                $column = $this->getCustomColumnName($name);
                unset($this->customColumns[$column]);
                $columns[] = $column;
            }
            foreach ($this->columns as $key => $column) {
                if (in_array($column, $columns)) {
                    unset($this->columns[$key]);
                }
            }
        } else {
            $columns = array();
            $column = $this->getCustomColumnName($columnName);
            unset($this->customColumns[$column]);
            $columns[] = $column;
        }
        foreach ($this->columns as $key => $column) {
            if (in_array($column, $columns)) {
                unset($this->columns[$key]);
            }
        }
    }

    /**
     * Add custom column after column
     *
     * @param string $afterColumnName
     * @param string $columnName
     * @param callback $callback
     */
    public function addCustomColumnAfterColumn($columnName, $customColumn) {
        $customColumn = $this->setDefaultCustomColumnsValue($customColumn);
        $this->customColumns[$customColumn['name']] = $customColumn;
        $columnName = $this->addPrefixEntity($columnName);
        $key = array_search($columnName, $this->columns);
        array_splice($this->columns, $key + 1, 0, $customColumn['name']);
    }

    /**
     * Add custom column as first column
     *
     * @param string $columnName
     * @param callback $callback
     */
    public function addCustomColumnAsFirstColumn($customColumn) {
        $customColumn = $this->setDefaultCustomColumnsValue($customColumn);
        $this->customColumns[$customColumn['name']] = $customColumn;
        array_unshift($this->columns, $customColumn['name']);
    }

    /**
     * Set default custom columns value
     *
     * @param array $customColumn
     */
    public function setDefaultCustomColumnsValue($customColumn) {
        $customColumn['label'] = isset($customColumn['label']) ?
                $customColumn['label'] : $this->getColumnName($customColumn['name']);
        $customColumn['callbackColumn'] = isset($customColumn['callbackColumn']) ?
                $customColumn['callbackColumn'] : null;
        return $customColumn;
    }

    /**
     * Add custom column
     *
     * @param array $customColumn
     */
    public function addCustomColumn($customColumn) {
        $customColumn = $this->setDefaultCustomColumnsValue($customColumn);
        $this->customColumns[$customColumn['name']] = $customColumn;
        $this->columns[] = $customColumn['name'];
    }

    /**
     * Add custom columns
     *
     * @param array $customColumns
     */
    public function addCustomColumns($customColumns) {
        foreach ($customColumns as $customColumn) {
            $this->addCustomColumn($customColumn);
        }
    }

    /**
     * Add formatter columns
     *
     * @param array $parameters
     * @return array
     */
    public function addFormatterColumns($parameters) {
        foreach ($parameters as $parameter) {
            $column = $parameter['column'];
            $callback = $parameter['callback'];
            $column = $this->addPrefixEntity($column);
            $this->formatterColumns[$column] = array(
                'callback' => $callback,
            );
        }
    }

    /**
     * Add formatter a column
     * @param string $column column name
     * @param callback $callback
     * @return array
     */
    public function addFormatterColumn($column, $callback) {
        $column = $this->addPrefixEntity($column);
        $this->formatterColumns[$column] = array(
            'callback' => $callback,
        );
    }

    /**
     * Set sortable columns
     *
     * @param array $columns columns name
     * @return array
     */
    public function setSortableColumns($columns) {
        $sortableColumns = array();
        foreach ($columns as $column) {
            $column = $this->addPrefixEntity($column);
            $sortableColumns[] = $column;
        }
        return $sortableColumns;
    }

    /**
     * Set search visibility
     *
     * @param boolean $status
     * @return boolean
     */
    public function setSearchVisibility($status) {
        $this->isSearchable = $status;
    }

    /**
     * Set multiple search visibility
     *
     * @param boolean $status
     * @return boolean
     */
    public function setMultipleSearchVisibility($status) {
        $this->isMultipleSearchable = $status;
    }

    /**
     * Set individual search visibility
     *
     * @param boolean $status
     * @return boolean
     */
    public function setIndividualSearchVisibility($status) {
        $this->isIndividualSearchable = $status;
    }

    /**
     * Set buttons visibility
     *
     * @param boolean $status
     * @return void
     */
    public function setButtonsVisibility($status) {
        $this->buttonsVisibility = $status;
    }

    /**
     * Set pagination info visibility
     *
     * @param boolean $status
     * @return void
     */
    public function setPaginationInfoVisibility($status) {
        $this->paginationInfoVisibility = $status;
    }

    /**
     * Clear column remove alias
     *
     * @param boolean $status
     * @return void
     */
    public function columnParts($column) {
        preg_match('/(?P<column>.+) AS (?P<alias>.+)/', $column, $matches);
        return $matches;
    }

    /**
     * Clear column remove alias
     *
     * @param boolean $status
     * @return void
     */
    public function clearColumn($column) {
        preg_match('/(?P<column>[^( AS )]+)(?= AS (?P<alias>.+))?/', $column, $matches);
        return $matches['column'];
    }

    /**
     * Clear columns from aliasing
     *
     * @return void
     */
    public function clearColumnsFromAliasing() {
        foreach ($this->columns as $index => $column) {
            $this->columns[$index] = $this->clearColumn($column);
        }
        foreach ($this->columns as $index => $column) {
            $this->columns[$index] = $this->clearColumn($column);
        }
    }

    /**
     * Add a "group by" clause to the query.
     *
     * @param  array|string  $column,...
     * @return $this
     */
    public function groupBy($parameters) {
        $this->groupBy = $parameters;
    }

    /**
     * Get group by
     *
     * @return array
     */
    public function getGroupBy() {
        return $this->groupBy;
    }

    /**
     * Add a join clause to the query.
     *
     * @param string $sourceTable
     * @param string $sourceColumn
     * @param string $operator
     * @param string $destinationColumn
     * @param string $joinType
     */
    public function join($sourceTable, $sourceColumn, $operator, $destinationColumn, $joinType = "INNER") {
        $relation = array($sourceTable, $sourceColumn, $operator, $destinationColumn, $joinType);
        $this->relation[] = $relation;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        $title = trans('dkscaffolding.' . $this->table);
        return ($title != 'dkscaffolding.' . $this->table) ? $title : mb_convert_case($this->table, MB_CASE_TITLE, "UTF-8");
    }

    /**
     * Set title
     *
     * return void
     */
    public function setTitle($value) {
        $this->title = $value;
    }

    /**
     * Is Create
     *
     * @return boolean
     */
    public function isCreate() {
        return ( $this->Request->action == "create" ) ? true : false;
    }

    /**
     * Is Edit
     *
     * @return boolean
     */
    public function isEdit() {
        return ( $this->Request->action == "edit" ) ? true : false;
    }

    /**
     * Is Delete
     *
     * @return boolean
     */
    public function isDelete() {
        return ( $this->Request->action == "delete" ) ? true : false;
    }

    /**
     * Is View
     *
     * @return boolean
     */
    public function isView() {
        return ( $this->Request->action == "view" ) ? true : false;
    }

    /**
     * Set relationship table
     *
     * @param Illuminate\Database\Eloquent\Model $Model
     * @param array $parameters
     * @return void
     */
    public function setRelationship($parameters) {
        foreach ($parameters as $parameter) {
            $sourceTable = $parameter['table'];
            $sourceColumn = $parameter['table'] . "." . $parameter['index'];
            $table = isset($parameter['source']) ? $parameter['source'] : $this->table;
            $destinationColumn = $table . "." . $parameter['foreign'];
            $this->Model = $this->Model->join($sourceTable, $sourceColumn, "=", $destinationColumn, "LEFT");
        }
    }

    /**
     * Set relationship table with multi condition
     *
     * @param Illuminate\Database\Eloquent\Model $Model
     * @param array $parameters
     * @return void
     */
    public function setRelationshipMultiCondition($parameters) {
        foreach ($parameters as $parameter) {
            $sourceTable = $parameter['table'];
            $sourceColumn = $parameter['table'] . "." . $parameter['index'];
            $destinationColumn = $parameter['foreign'];
            $this->Model = $this->Model->join($sourceTable, $sourceColumn, "=", DB::raw($destinationColumn), "LEFT");
        }
    }

    public function categoryNameFormatter($record) {
        ?>
        <span class="label label-info"><?php echo $record->name; ?></span>
        <?php
    }

    public function dateFormatter($record) {
        echo date('d F Y', strtotime($record->date));
    }

    public function getStatus($record) {
        ?>
        <div><?php echo date("d F Y", strtotime($record->date)); ?></div>
        <?php
    }

}
