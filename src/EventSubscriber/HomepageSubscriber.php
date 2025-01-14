<?php

namespace App\EventSubscriber;

use App\Event\HomePage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HomepageSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'homepage.event' => 'onHomePage',
        ];
    }

    public function onHomePage(HomePage $event): void {
        $date = $event->date;
        // ...
    }
}