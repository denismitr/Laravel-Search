<?php

namespace Denismitr\Search\Searchers;


abstract class Searcher
{
    //Query strings after being exploded
    protected $queries = [];

    //Results of single queries
    protected $results = [];

    //Model name
    protected $model = null;

    //Table search field
    protected $searchField = '';


    public function __construct($query)
    {
        $this->queries = explode(' ', $query);
    }

    /**
     * Run the queries and prepare the result
     *
     * @return mixed
     */
    public function get()
    {
        $this->gatherResults();

        return $this->reduceResultsToSingleCollection();
    }


    /**
     * Gather results from all the queries
     */
    protected function gatherResults()
    {
        foreach($this->queries as $query) {
            if (mb_strlen($query) > 2) {
                $this->results[] = $this->query($query);
            }
        }
    }


    /**
     * Take all the results array and reduce it
     *  to a single Collection< then delete double items
     *
     * @return mixed
     */
    protected function reduceResultsToSingleCollection()
    {
        $collection = array_reduce($this->results, function($collection1, $collection2) {
            if ($collection1) {
                return $collection1->merge($collection2 ?: []);
            }

            return $collection2 ?: [];
        });

        return $collection->unique();
    }

}
