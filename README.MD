#Laravel Search Engine

Maybe that's a little exaggerated, since i've build this library for just one project i was building. 
However it worked like a charm, so I've decided to publish it.

##Installation

    composer require denismitr/search

##Useage

    $records = Search::query('some query string or strings go here')
                    ->withSearchers([
                        TitleSearcher::class,
                        DescriptionSearcher::class,
                        SomeRelationalSearcher::class
                    ])->get();

Now here is the tricky part: there are two `abstract` searcher classes `SimpleSearcher` and `RelationalSearcher` 
from which to inherit all the __searcher__ classes and a common interface `Searchable` to implement. 
`SimpleSearcher` is of course for simple Eloquent model search and `RelationalSearcher` for when a __relationship__ invloved. 
Like so:

```php
<?php

namespace App\Queries;

use App\Post;
use Denismitr\Search\Searchable;
use Denismitr\Search\Searchers\SimpleSearcher;

class TitleSearcher extends SimpleSearcher implements Searchable
{
    //Here you specify a Eloquent model class which you search and the collection of which you whant to get
    //as a return of the search
    protected $model = Post::class;

    //Here you specify the field you in which to search
    protected $searchField = 'title';
}
```

and another more complex `RelationalSearcher` for searching parent models like a topic of a post

```php
<?php

namespace App\Queries;

use App\Post;
use App\Topic;
use Denismitr\Search\Searchable;
use Denismitr\Search\Searchers\RelationalSearcher;

class TopicNameSearcher extends RelationalSearcher implements Searchable
{
    protected $model = Post::class;
    protected $searchField = 'name';

    //Here you specify the Eloquent relational methods (that return hasMany or belongstoMany)
    protected $relation = 'topics';
}
```

Than you put all that stuff in the `withSearchers` method of the __Search__ class and you good to go.

##Attention
Every `Search` instance on `get()` returns a collection of single Eloquent model filtering out all duplicates. If
you need to output results for whole different unrelated tables you'll need more than one Search instance and more sets
of Searchers. I know it's a bit tidious. If I have more time I'll turn this into something more convinient. 
_And also if you need pagination at this point you have to do it manually. With Laravel it's easy._
