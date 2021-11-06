# PHP Router

This is a Simple PHP router.

## Installation

Just download the router.php file and require it in in the file on which you plan to defile your routes.



## Usage

```php
require_once('router.php');

$router = new Router();

$router->Handler('POST', '/user/{id}', 'userController');
$router->Handler('GET', '/about', function() {
    echo '<h2>About</h2>';
});
$router->get('/contactus', 'contactController');

$router->run();
```

Handler Function requires 3 inputs HTTP method name, route and Function respectively.

In place of handler function you can use $router->get, post, put, delete the only difference is that you need to pass just 2 variables which are route and function respectively. 

### Passing Variable in route
Use {} curly brackets to pass variables in route for example {id}.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
