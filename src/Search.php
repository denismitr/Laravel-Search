<?php

namespace Denismitr\Search;

use App\Denismitr\Search\Exceptions\WrongSearchResultsType;
use App\Denismitr\Search\Exceptions\WrongSearcher;
use Illuminate\Pagination\LengthAwarePaginator;

/**
* Search class
*/
class Search
{
    //An array of searcher classes
    protected $searchers;

    //Query from request
    protected $query;

    //Instanciated searchers
    protected $searchersInstances = [];

    //Obtained results
    protected $results;

    /**
     * Search constructor.
     *
     * @param $query
     * @param array $searchers
     */
    public function __construct($query, array $searchers = [])
    {
        $this->query = trim($query);
        $this->searchers = $searchers;

        if (!empty($searchers)) {
            $this->initializeSearchers();
        }
    }

    /**
    * Instantiate the Search class
    *
    * @param [string] $query
    * @return Search Object
    */
    public static function query($query) {
        return new static($query); 
    }

    /**
     * Provide an array of searchers
     *
     * @param array $searchers
     * @return $this
     * @throws WrongSearcher
     */
    public function withSearchers(array $searchers)
    {
        $this->searchers = $searchers;

        $this->initializeSearchers();

        return $this;
    }


    /**
     * Get the search results
     *
     * @return mixed
     */
    public function get()
    {
       $this->conductSearch();

       return $this->reduceResultsToOneCollection();
    }


    /**
     * Get and paginate results
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     * @throws WrongSearchResultsType
     */
    public function paginate($perPage = 10)
    {
        $this->conductSearch();

        return $this->paginateResults($perPage);
    }


    /**
     * TODO: Tesing
     *
     * @param $perPage
     * @return LengthAwarePaginator
     * @throws WrongSearchResultsType
     */
    protected function paginateResults($perPage)
    {
        $page = Input::get('page', 1);

        $offset = ($page * $perPage) - $perPage;

        $results = $this->reduceResultsToOneCollection()->toArray();

        if ( ! is_array($results)) {
            throw new WrongSearchResultsType("Obtained results object is illegal!");
        }

        $paginator = new LengthAwarePaginator(
            array_slice($results, $offset, $perPage),
            count($results),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginator;
    }


    /**
     * Take all the obtained results and combine them into
     * one single collection removing all the doubles
     *
     * @return mixed
     */
    protected function reduceResultsToOneCollection()
    {
        $collection = array_reduce($this->results, function($collection1, $collection2) {
            if ($collection1) {
                return $collection1->merge($collection2 ?: []);
            }

            return $collection2 ?: [];
        });

        return $collection->unique();
    }


    /**
     * Instansciate all the searcher instances that are provided
     *
     * @throws WrongSearcher
     */
    protected function initializeSearchers()
    {
        foreach ($this->searchers as $searcher) {
            if (in_array(Searchable::class, class_implements($searcher))) {
                $this->searchersInstances[] = new $searcher($this->query);
            } else {
                throw new WrongSearcher("{$searcher} is a wrong class for Search");
            }
        }
    }


    /**
     * Conduct the search using the searcher Instances
     * and then save every result to the $this->results
     */
    protected function conductSearch()
    {
        foreach ($this->searchersInstances as $searcher) {
            $this->results[] = $searcher->get();
        }
    }
}
