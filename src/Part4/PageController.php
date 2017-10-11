<?php

namespace Tutorial\Part4;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function pageAction(Request $request, $url)
    {
        return new Response('page action - url:'.$url);
    }
}
