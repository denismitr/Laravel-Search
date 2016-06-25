<?php

namespace Denismitr\Search\Searchers;

use Denismitr\Search\Exceptions\WrongModelProvided;

abstract class SimpleSearcher extends Searcher
{

    /**
     * Make a simple Model query
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
            ->where($this->searchField, 'LIKE', '%'.$query.'%')
            ->get();
    }
}