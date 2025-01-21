<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Symfony\Component\String\u;

/**
 * - do  a redirect from / to /locale
 * - do redirect from /unsupported to /locale
 */
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

        /** @todo move languages to params */
        $preferredLanguage = $request->getPreferredLanguage(['en', 'uk']);
        if($preferredLanguage === null) {
            $preferredLanguage = 'en';
        }
        
        $path = $request->getPathInfo();
        $isMain = $event->isMainRequest();
        // Ignore sub-requests and all URLs but the homepage
        if (!$isMain || '/' !== $path) {
            $locale = $request->getLocale();
            /** @todo move to params */
            if(in_array($locale, ['en', 'uk'])) {
                return;
            } else {
                $response = new RedirectResponse(
                    /** @todo move to params */
                    sprintf('/%s%s', $preferredLanguage, $path)
                );
                $event->setResponse($response);
                return;
            }
            
        }

        // Ignore requests from referrers with the same HTTP host in order to prevent
        // changing language for users who possibly already selected it for this application.
        $referrer = $request->headers->get('referer');

        if (null !== $referrer && u($referrer)->ignoreCase()->startsWith($request->getSchemeAndHttpHost())) {
            return;
        }

        $url = $this->urlGenerator->generate(
            'homepage_index',
            ['_locale' => $preferredLanguage]
        );

        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
}
