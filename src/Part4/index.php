<?php

namespace Tutorial\Part4;

require_once __DIR__.'/../../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

$app = new Application(array('debug' => true));

//registering service providers
//without this Silex wont resolve controller names

$app->register(new ServiceControllerServiceProvider());

//loading routes

try {
    $routeArray = Yaml::parse(file_get_contents(__DIR__.'/routes.yml'));
} catch (ParseException $e) {
    printf('Unable to parse the YAML string: %s', $e->getMessage());
}

$routes = new RouteCollection();

foreach ($routeArray as $key => $value) {
    $route = $app['route_factory'];
    foreach ($value as $property => $propertyValue) {
        switch ($property) {
            case 'path':
                $route->setPath($propertyValue);
                break;
            case 'defaults':
                $route->setDefaults($propertyValue);
                break;
            case 'methods':
                $route->setMethods($propertyValue);
                break;
            default:
                if (method_exists($route, $property)) {
                    $route->$property($propertyValue);
                } else {
                    $method = sprintf('set%s', ucfirst($property));
                    if (!method_exists($route, $method)) {
                        throw new \RuntimeException(sprintf("%s does not have method '%' nor '%'!", get_class($route), $property, $method));
                    }
                    $route->$method($propertyValue);
                }
                break;
        }
    }
    $routes->add($key, $route);
}

$app['routes']->addCollection($routes);

//registering controllers

$app['DefaultController'] = function ($a) {
    return new DefaultController($a);
};

$app['PageController'] = function ($a) {
    return new PageController($a);
};

$app->run();
