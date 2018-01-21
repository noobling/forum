# Notes

## Testing

`phpunit --filter method_name` test a single method

## Misc
$thread->replies()->count() will go through all the replies just to get the count this is inefficient instead do this `$thread->replies()->count()`

Make a query available to all models
````
protected static function boot()
{
    parent::boot();

    static::addGlobalScope('replyCount', function($builder) {
        $builder->withCount('replies');
    });
}
````
Gives replies_count property to all $threads

## Pluralisation
str_plural('string', integer)

## Filtering
When your filter methods don't work you can use `model->toSql()` before the `get()` method.