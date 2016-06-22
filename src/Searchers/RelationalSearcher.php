<?php

namespace App\Denismitr\Search\Searchers;


use Illuminate\Support\Facades\DB;

abstract class RelationalSearcher extends Searcher
{
    //Relation method name (not instance or class o)
    protected $relation;


    /**
     * Make a query throw main model but get only those results that
     * correspond to relation table query parameters
     *
     * @param $query
     * @return mixed
     */
    protected function query($query)
    {
        $modelInstance = new $this->model;

        return $modelInstance
            ->whereHas($this->relation, function($queryInstance) use ($query) {
                $queryInstance->where($this->searchField, 'LIKE', '%' . $query . '%');
            })->get();
    }
}