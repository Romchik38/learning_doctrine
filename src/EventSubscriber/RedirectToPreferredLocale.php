<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Symfony\Component\String\u;

final class RedirectToPreferredLocale implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $path = $request->getPathInfo();
        $isMain = $event->isMainRequest();
        // Ignore sub-requests and all URLs but the homepage
        if (!$isMain || '/' !== $path) {
            return;
        }

        // Ignore requests from referrers with the same HTTP host in order to prevent
        // changing language for users who possibly already selected it for this application.
        $referrer = $request->headers->get('referer');

        if (null !== $referrer && u($referrer)->ignoreCase()->startsWith($request->getSchemeAndHttpHost())) {
            return;
        }

        /** @todo move languages to params */
        $preferredLanguage = $request->getPreferredLanguage(['en', 'uk']);
        $response = new RedirectResponse(
            $this->urlGenerator->generate(
                'homepage_index',
                ['_locale' => $preferredLanguage]
            )
        );
        $event->setResponse($response);
    }
}
