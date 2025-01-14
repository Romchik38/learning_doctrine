<?php

// src/EventListener/RequestListener.php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        // ...
        // $result = $request->query->get('page');
        // ...
    }
}