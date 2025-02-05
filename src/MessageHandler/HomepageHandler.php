<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\Homepage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
final class HomepageHandler
{

    public function __construct(
        protected readonly LoggerInterface $logger
    ) {}

    public function __invoke(Homepage $message)
    {
        $date = $message->date;
        $this->logger->debug($date->format('Y-m-d H:i:s'));
    }
}
