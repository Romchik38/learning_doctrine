<?php

// src/EventListener/RequestListener.php
namespace App\EventListener;

use App\Event\HomePage;

class HomepageListener
{
    public function __invoke(HomePage $event): void
    {
        $date = $event->date;
        // ...
        // $result = $request->query->get('page');
        // ...
    }
}