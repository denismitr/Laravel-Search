<?php

namespace App\Denismitr\Search\Searchers;


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
        $modelInstance = new $this->model;

        return $modelInstance
            ->where($this->searchField, 'LIKE', '%'.$query.'%')
            ->get();
    }
}