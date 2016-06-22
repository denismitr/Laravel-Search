<?php

namespace App\Denismitr\Search\Searchers;
use App\Denismitr\Search\Searchable;
use App\Painting;

/**
* Search the spec field in the painting table
*/
class EnglishSpecSearcher extends SimpleSearcher implements Searchable
{
    protected $model = Painting::class;
    protected $searchField = 'spec_en';
}