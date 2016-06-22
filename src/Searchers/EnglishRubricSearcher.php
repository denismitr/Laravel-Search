<?php

namespace App\Denismitr\Search\Searchers;

use App\Denismitr\Search\Searchable;
use App\Painting;

/**
* Search the year field in the painting table
*/
class EnglishRubricSearcher extends RelationalSearcher implements Searchable
{
    protected $model = Painting::class;
    protected $searchField = 'title_en';
    protected $relation = 'rubric';
}