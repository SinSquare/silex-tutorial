<?php

namespace Tutorial\Part2;

require_once __DIR__.'/../../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application(array('debug' => true));

$app->get('/', function () use ($app) {
    return new Response('Hello from home');
});

$app->get('/p/{param}', function ($param) use ($app) {
    return new Response('Hello from param route. The prame value:'.$param);
});

$app->get('/json', function () use ($app) {
    return new JsonResponse(array('timestamp' => time()));
});

$app->before(function (Request $request, Application $app) {
    $request->attributes->set('custom', 'custom value');
});

$app->after(function (Request $request, Response $response) {
    if ($response instanceof JsonResponse) {
        $data = $response->getContent();
        $dataArr = json_decode($data, true);
        if (!is_array($dataArr)) {
            return;
        }
        $dataArr['custom'] = $request->attributes->get('custom');
        $response->setContent(json_encode($dataArr));
    }
});

$app->run();
