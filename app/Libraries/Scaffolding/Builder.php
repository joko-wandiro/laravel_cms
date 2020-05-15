<?php

namespace App\Libraries\Scaffolding;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

class Builder extends EloquentBuilder
{

    /**
     * Paginate the given query.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);
        $perPage = $perPage ?: $this->model->getPerPage();
        if ($perPage != "all") {
            $this->forPage($page, $perPage);
        }
        // Get records
        $records= $this->runQuery($this->toSql(), $this->getBindings());
        // Get row count of SQL_CALC_FOUND_ROWS
        $sql= 'SELECT FOUND_ROWS() AS total';
        $resultFoundRows= $this->runQuery($sql)->toArray();
        $total= $resultFoundRows[0]['total'];
        // Set default per page for all value while total is empty
        if ($perPage == "all") {
            $perPage= ($total) ? $total : 10;
        }
        return new LengthAwarePaginator($records, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
    * Run SQL Query
    *
    * @param string $query
    * @param array $bindings
    *
    * @return
    */
    public function runQuery($query, $bindings = array())
    {
        $Connection= $this->getConnection();
        $result= $this->model->hydrate($Connection->select($query, $bindings));
        return $result;
    }
}
