<?php

namespace App\Libraries\Scaffolding;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Builder as Builder;
use App\Libraries\Scaffolding\Builder as ScaffoldingBuilder;
use Carbon\Carbon;
use Form;
use Gate;
use Auth;
use DB;

/**
 * App\Libraries\Scaffolding\Model
 *
 * @method static \Illuminate\Database\Query\Builder
 * @mixin \Eloquent
 */
class Model extends BaseModel
{
    /**
     * Columns
     *
     * @var array
     */
    public $columns;

    /**
     * BIT columns
     *
     * @var array
     */
    public $bitColumns = array();

    /**
     * BLOB columns
     *
     * @var array
     */
    public $blobColumns = array();
    
    /**
     * Columns information
     *
     * @var array
     */
    public $columnsInformation;

    /**
     * Primary key columns
     *
     * @var array
     */
    public $indexes;
        
    /**
     * Layout
     *
     * @var array
     */
    public $layout = array();

    /**
     * Validation rules
     *
     * @var array
     */
    public $validationRules = array();

    /**
     * Validation rules javascript
     *
     * @var array
     */
    public $validationRulesJS = array();
            
    /**
     * Get default column definition
     *
     * @return array
     */
    public function getDefaultColumnDefinition()
    {
        return array(
            'attributes'=>array(
                'class' => 'form-control',
            ),
        );
    }

    /**
     * Get default layout definition
     *
     * @return array
     */
    public function getDefaultLayoutDefinition()
    {
        return array(
            'attributes'=>array(
                'class' => 'col-sm-12',
            ),
        );
    }
        
    /**
     * Get label
     *
     * @return array
     */
    protected function getLabel($column)
    {
    	$label= 'dkscaffolding.column.' . $column;
    	$value= trans('dkscaffolding.column.' . $column);
    	return ( $value == $label ) ? mb_convert_case(str_replace("_", " ", $column), MB_CASE_TITLE, "UTF-8") : $value;
    }

    /**
     * Get label
     *
     * @return array
     */
    protected function toUpper($string)
    {
        return mb_convert_case($string, MB_CASE_UPPER, "UTF-8");
    }

     /**
     * Get primary key columns
     *
     * @return array
     */
    public function getIndexes()
    {
        return $this->indexes;
    }
    
    /**
    * Set primary key columns
    *
    * @param array $columns
    *
    * @return $this
    */
    public function setIndexes($columns)
    {
        $this->indexes= $columns;
        return $this;
    }
    
    /**
    * Set initial configuration
    *
    * @param string $tableName
    *
    * @return $this
    */
    public function build($tableName)
    {
        $this->setTable($tableName);
        $columns= $this->runQuery('EXPLAIN '.$this->getTable())->toArray();
        // Set index columns
        $indexes= array();
        foreach ($columns as $column) {
            // Set indexes
            if ($column['Key'] == "PRI") {
                $indexes[] = $column['Field'];
            }
        }
        $this->setIndexes($indexes);
        $Request= request();
        // Get HTTP Verb
        $httpVerb= $Request->getMethod();
        $dateColumns= $this->getDates();
        $defaultColumnDefinition= $this->getDefaultColumnDefinition();
        $defaultLayoutDefinition= $this->getDefaultLayoutDefinition();
        // Set fillable columns
        foreach ($columns as $column) {
            // Remove created_at and updated_at
            if ($this->timestamps && in_array($column['Field'], $dateColumns)) {
                continue;
            }
            // Remove auto increment columns for create action
            if ($column['Extra'] == "auto_increment" &&
            ( $httpVerb == "POST" || $Request['action'] == "create" ) ) {
                continue;
            }
            // Set initial value of validationRules column
            $this->validationRules[$column['Field']]= '';
            $parameters= $defaultColumnDefinition;
            $defaultLayout= $defaultLayoutDefinition;
            // Get data type, length and range
            $matches= array();
            preg_match(
                '/^(?P<datatype>[[:alpha:]]+)(\((?P<length>.+)\))?( (?P<range>.+))?$/',
                $column['Type'],
                $matches
            );
            $parameters['name']= $column['Field'];
            $label= $this->getLabel($column['Field']);
            $parameters['label']= $label;
            $parameters['attributes']['placeholder']= $label;
            $parameters['dataType']= isset($matches['datatype']) ? $this->toUpper($matches['datatype']) : null;
            $parameters['length']= isset($matches['length']) ? $matches['length'] : null;
            $parameters['range']= isset($matches['range']) ? $matches['range'] : null;
            // Set input element type
            $parameters['type']= 'text';
            switch ($parameters['dataType']) {
                case "BIT":
                    if ((int)$parameters['length'] == 1) {
                        $parameters['attributes']= array();
                        $parameters['type']= 'checkbox';
                        $this->validationRules[$column['Field']].= '|boolean';
                        $this->bitColumns[]= $parameters['name'];
                        break;
                    }
                case "TINYINT":
                case "SMALLINT":
                case "MEDIUMINT":
                case "INT":
                case "BIGINT":
                    $parameters['attributes']['class'].= " dk-number";
                    $this->validationRules[$column['Field']].= '|numeric';
                    $this->validationRulesJS[$column['Field']]['digits']= true;
                    break;
                case "DOUBLE":
                case "FLOAT":
                case "DECIMAL":
                    $parameters['attributes']['class'].= " dk-float";
                    $this->validationRules[$column['Field']].= '|numeric';
                    $this->validationRulesJS[$column['Field']]['number']= true;
                    break;
                case "CHAR":
                case "VARCHAR":
                case "TINYTEXT":
                    $length= ($parameters['dataType'] == "TINYTEXT") ? 255 : $parameters['length'];
                    $this->validationRules[$column['Field']].= '|max:'.$length;
                    $this->validationRulesJS[$column['Field']]['maxlength']= $length;
                case "ENUM":
                    $parameters['attributes']['class'].= " dk-character";
                    $this->validationRules[$column['Field']].= '|string';
                    break;
                case "TEXT":
                case "MEDIUMTEXT":
                case "LONGTEXT":
                    $parameters['type']= 'textarea';
                    $parameters['attributes']['class'].= " dk-textarea";
                    break;
                case "BINARY":
                case "VARBINARY":
                case "TINYBLOB":
                case "BLOB":
                case "MEDIUMBLOB":
                case "LONGBLOB":
                    $parameters['type']= 'file';
                    $parameters['attributes']['class'].= " dk-file";
                    $this->blobColumns[]= $parameters['name'];
                    break;
                case "DATE":
                    $parameters['attributes']['class'].= " dk-date";
                    $this->validationRules[$column['Field']].= '|date_format:Y-m-d';
                    $this->validationRulesJS[$column['Field']]['date']= true;
                    break;
                case "DATETIME":
                case "TIMESTAMP":
                    $parameters['attributes']['class'].= " dk-datetime";
                    $this->validationRules[$column['Field']].= '|date_format:Y-m-d H:i:s';
                    $this->validationRulesJS[$column['Field']]['date']= true;
                    break;
                case "TIME":
                    $parameters['attributes']['class'].= " dk-time";
                    $this->validationRules[$column['Field']].= '|date_format:H:i:s';
                    break;
                case "YEAR":
                    $parameters['attributes']['class'].= " dk-year";
                    if ($parameters['length'] == "2") {
                        $this->validationRules[$column['Field']].= '|date_format:y';
                    } elseif ($parameters['length'] == "4") {
                        $this->validationRules[$column['Field']].= '|date_format:Y';
                    }
                    $this->validationRulesJS[$column['Field']]['date']= true;
                    break;
                default:
                    $parameters['attributes']['class'].= " dk-default";
            }
            // Define input type for password
            $regexInputPassword= '/password/';
            if (preg_match($regexInputPassword, $column['Field'])) {
                $parameters['type']= 'password';
            }
            // Add extra class for email
            $regexInputEmail= '/email/';
            if (preg_match($regexInputEmail, $column['Field'])) {
                $parameters['attributes']['class'].= " dk-email";
                $this->validationRules[$column['Field']].= '|email';
                $this->validationRulesJS[$column['Field']]['email']= true;
            }
            // Define unique validaton
            if ($column['Key'] == "UNI" && count($indexes) == 1) {
                $this->validationRules[$column['Field']].= '|unique:'.$this->getTable().','.$column['Field'].','.$Request[$indexes[0]].','.$indexes[0];
            }
            // Define parameters according to the column Key
            switch ($column['Key']) {
                case "PRI":
                    $parameters['type']= 'hidden';
                    break;
                case "MUL":
                    $parameters['type']= 'select';
                default:
                    // Add column into layout
                    $defaultLayout['name']= $parameters['name'];
                    $this->layout[]= array($defaultLayout);
            }
            // Define require column
            $parameters['require']= false;
            if ($column['Null'] == "NO" && $column['Extra'] != "auto_increment") {
                // Set require column except file in UPDATE action
                $requireStatus= true;
                if ($parameters['type'] == "file" && ( $httpVerb == "PUT" ||
                $Request['action'] == "edit" ) ) {
                    $requireStatus= false;
                }
                if ($requireStatus) {
                    $parameters['require']= true;
                    $this->validationRules[$column['Field']].= '|required';
                    $this->validationRulesJS[$column['Field']]['required']= true;
                }
            } else {
                $this->validationRules[$column['Field']].= '|nullable';
            }
            $this->columns[$parameters['name']]= $parameters;
            $this->columnsInformation[$parameters['name']]= $column;
            $this->fillable[]= $column['Field'];
            // Remove first character on validationRules
            if ($this->validationRules[$column['Field']]) {
                $this->validationRules[$column['Field']]= substr($this->validationRules[$column['Field']], 1);
            }
        }
        return $this;
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return static
     */
    public function dkCreate(array $attributes = [])
    {
        return $this->fill($attributes)->save();
    }
    
    /**
     * Set the keys for a save update query.
     *
     * @param  \App\Libraries\Scaffolding\Builder  $query
     * @return \App\Libraries\Scaffolding\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $indexes= $this->getIndexes();
        foreach ($indexes as $column) {
            $value= isset($this->original[$column]) ? $this->original[$column] : $this->getAttribute($column);
            $query->where($column, '=', $value);
        }
        return $query;
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new ScaffoldingBuilder($query);
    }
}
