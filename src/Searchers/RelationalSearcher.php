<?php

namespace Denismitr\Search\Searchers;

use Denismitr\Search\Exceptions\WrongModelProvided;

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
        if ( ! $this->model || ! class_exists($this->model)) {
            throw new WrongModelProvided("Propery `model` has not been provided or has been provided with some error!");
        }
		
		$modelInstance = new $this->model;

        return $modelInstance
            ->whereHas($this->relation, function($queryInstance) use ($query) {
                $queryInstance->where($this->searchField, 'LIKE', '%' . $query . '%');
            })->get();
    }
}
