[![Latest Stable Version](https://poser.pugx.org/douma/laravel-database-routes/v/stable)](https://packagist.org/packages/douma/laravel-controller-plugins)
[![Total Downloads](https://poser.pugx.org/douma/laravel-database-routes/downloads)](https://packagist.org/packages/douma/laravel-controller-plugins)
[![Monthly Downloads](https://poser.pugx.org/douma/laravel-database-routes/d/monthly)](https://packagist.org/packages/douma/laravel-controller-plugins)
[![Latest Unstable Version](https://poser.pugx.org/douma/laravel-database-routes/v/unstable)](https://packagist.org/packages/douma/laravel-controller-plugins)
[![License](https://poser.pugx.org/douma/laravel-database-routes/license)](https://packagist.org/packages/douma/laravel-controller-plugins)

# Laravel Route Manager for MySQL

![](https://unik.al/unik_content/uploads/2018/12/laravel-logo.png)

This `Route manager` component for Laravel is 
an extra layer on top of Laravel routing mechanism to 
be able to store routes in the database. 

## Installation

`composer require douma/laravel-database-routes`

### Db table `routes`

Create the following table:

```sql
CREATE TABLE `routes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `is_pattern` int(1) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_unique` (`url`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8
```

### Service Provider

#### Db service provider (default)

`Douma\Routes\ServiceProviders\DbServiceProvider::class`.
This service provider loads routes from the database, the `routes` table. 

#### In memory service provider

`Douma\Routes\ServiceProviders\InMemoryServiceProvider::class`.
This service provider loads routes from the memory.

### Route middleware

Register the `\Douma\Routes\Middleware\RouteMiddleware::class` within `App\Http\Kernel.php`.

If the `DbServiceProvider` enabled, the `RouteMiddleware` only loads the active route into memory.
This leads to increased performance. 

## Using the `RouteManager`

Anywhere you need the `RouteManager` you can inject `Douma\Routes\Contracts\RouteManager`
into the constructor. Based on the selected service provider the right
`RouteManager` will load. 

## Best practise

### Use a cli command to load routes into the database 

For example, with `php artisan routes:generate`.

```php
use Douma\Routes\Contracts\RouteManager;

class RoutesGenerateCommand extends Command 
{
    protected $signature = 'routes:generate';
    
    private $routeManager;
    
    public function __construct(RouteManager $routeManager, /* Your other dependencies */)
    {
        $this->routeManager = $routeManager;
    }
    
    public function handle()
    {
        $this->info('Generating routes');
        $this->routeManager->addRoute(
            new Route('/my-route',false, 'test1', Controller::class, 'index')
        );
    }
}
```

Run the command for example every 2-5 minutes.

### Add a route with a pattern

```php
    $this->routeManager->addRoute(
    new Route(
        '/test-test/{id}', true, 'test01234', Controller::class, 'index'
    ));
```

### Create routes with a named construct

Hide the construction logic in a named construct, for different routes. 

```php
class MyPageRoute extends Route
{
    public static function from(Page $page)
    {
        return new self(
            "/" . $page->getColumn('slug'), false, "page" .$page->getColumn('id'),
            PageController::class, 'index'
        );
    }
}
```

You can use the construct in the `RoutesGenerateCommand`-command. 

## Loading routes based on name

You need routes to be available elsewhere in your code. Since the routes
are loaded in the database, you can load the `RouteManager` anywhere and
ask for the route by name:

```php
$route = $this->routeManager->routeByName('page1');
$url= $route->url();
```

### Route not found 

By default if the route is not found, a `NullObject` is returned with an url `#`.
If you want to catch the `NullObject`, compare to the `NullObject` or the url:

```php
if($route == \Douma\Routes\Route::$NULL) {
    //... your fallback code
}

//...or compare to the anchor
if($url == '#') {
    //... your fallback code
}
```

## Storing routes into other databases

Create your own implementation of `DbRouteManagerProxy`.

## Mutating routes

Routes are immutable by default. So for every mutation a new
route is returned. If you wish to alter any route argument,
simply use the following functions:

```php
$route = new Route('/my-route', false, 'test1', Controller::class, 'index');
//...or
$route = $this->routeManager->routeByName('test1');

//mutations 
$newRoute = $route->withName('test2');
$newRoute = $newRoute->withUrl('/my-new-route');
$newRoute = $newRoute->withIsPattern(false);
$newRoute = $newRoute->withController(Controller2::class);
$newRoute = $newRoute->withAction('another-action');
```

Or chain:

```php
$route = new Route('/my-route', false, 'test1', Controller::class, 'index');
echo $route->withUrl('/product')
    ->withGetParameters(['a'=>'b'])
    ->url();
```

### Get parameters

If you would like to use get parameters:

```php
$route = new Route('/my-route', false, 'test1', Controller::class, 'index');

//mutations 
$newRoute = $route->setGetParameters([
    'id'=>1
]); 

//output: /my-route?id=1
echo $newRoute->url();
```

### Pattern parameters

If you would like to replace pattern parameters, simply use:

```php
$route = new Route('/my-route/{id}', true, 'test1', Controller::class, 'index');

//mutations 
$newRoute = $route->setParameters([
    'id'=>1
]); 

//or...
$newRoute = $route->setParameter('id', 1); 

//output: /my-route/1
echo $newRoute->url();
```

## Using routes in blade

Register an alias in `app.php`:

```php
'aliases'=>[
    //...
    'RouteManager' => Douma\Routes\Facades\RouteManager::class,
]
```

You can use the `RouteManager`-facade in blade views:

```php
{{ RouteManager::routeByName('test')->url() }}
```
