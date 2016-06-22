<?php

namespace App\Denismitr\Search\Searchers;

use App\Denismitr\Search\Searchable;
use App\Painting;

/**
* Search the year field in the painting table
*/
class YearSearcher extends SimpleSearcher implements Searchable
{
    protected $model = Painting::class;
    protected $searchField = 'year';
}