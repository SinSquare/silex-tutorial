<?php

namespace Tutorial\Part4;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function homeAction(Request $request)
    {
        return new Response('home action');
    }
}
