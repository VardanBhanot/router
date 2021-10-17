<?php
require_once('router.php');
require_once('functions.php');

$router = new Router();

$router->Handler('GET', '/home/{id}', 'homeController');
$router->Handler('GET', '/', 'homeController');

$router->Handler('GET', '/about', function() {
    echo '<h2>About</h2>';
});

$router->Handler('GET', '/contactus', 'contactController');

$router->run();


?>