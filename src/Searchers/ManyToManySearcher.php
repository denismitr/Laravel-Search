<?php

namespace Denismitr\Search\Searchers;

use Denismitr\Search\Exceptions\WrongModelProvided;
use Denismitr\Search\Exceptions\WrongRelationProvided;


abstract class ManyToManySearcher extends Searcher
{
    //Relation method name (not instance or class o)
    protected $relation;

    //Parent model class
    protected $parentModel;


    /**
     * Make a query throw main model but get only those results that
     * correspond to relation table query parameters
     *
     * @param $query
     * @return mixed
     */
    protected function query($query)
    {
        if ( ! $this->parentModel || ! class_exists($this->parentModel)) {
            throw new WrongModelProvided("Propery `parentModel` has not been provided or has been provided with some error!");
        }

        $modelInstance = new $this->parentModel;

        if ( ! method_exists($modelInstance, $this->relation) {
            throw new WrongRelationProvided("Property `relation` has been provided with some error or has been forgotten");
        }

        return $modelInstance->{$this->relation}()->where($this->searchField, 'LIKE', '%' . $query . '%')->get();
    }
}
