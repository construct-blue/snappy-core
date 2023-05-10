# snappy-renderer

## A component oriented server side rendering engine for PHP

### Example usage

```php
<?php 
// A class component
class App implements \SnappyRenderer\Renderable {
    public function render(object $model) : iterable
    {
       return [
           '<html lang="en">',
                '<head>'
                    '<title>Example App</title>'
                '</head>'
                '<body>'
                    include 'greeting.php'
                '</body>'
           '</html>'
       ];
    }
}
```

```php
<?php
// A functional component greeting.php
return fn($model) => yield <<<HTML
    <h1>The Example Greeting</h1>
    <p>Hello $model->name</p>
HTML;
```

```php
$renderer = new \SnappyRenderer\Renderer();
echo $renderer->execute(new App(), (object)['name' => 'world'])
```
