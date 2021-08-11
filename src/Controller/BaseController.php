<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class BaseController extends AbstractController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function redirectToReferer(string $routeName = '', array $routeParameters = []): RedirectResponse
    {
        $url = $this->getRefererUrl();

        /*
         * Url of referer is unknown?
         * Let's build proper url
         */
        if ($url === null) {
            $url = '/';

            if (!empty($routeName)) {
                $url = $this->generateUrl($routeName, $routeParameters);
            }
        }

        return $this->redirect($url);
    }

    protected function getRefererUrl(): ?string
    {
        return $this
            ->requestStack
            ->getCurrentRequest()
            ->headers
            ->get('referer')
        ;
    }
}
