<?php

namespace App\Denismitr\Search\Searchers;
use App\Denismitr\Search\Searchable;
use App\Painting;

/**
* Search the spec field in the painting table
*/
class KeywordsSearcher extends SimpleSearcher implements Searchable
{
    protected $model = Painting::class;
    protected $searchField = 'keywords';
}