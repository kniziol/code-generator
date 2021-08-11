<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Base controller with common functionality
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
abstract class BaseController extends AbstractController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Redirects to the referer or (if empty and not exists) to url based on given route and parameters
     *
     * @param string $routeName       (optional) Name of the route which is used to redirect when the referer url is
     *                                empty / unknown
     * @param array  $routeParameters (optional) An array of parameters for given route
     * @return RedirectResponse
     */
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

    /**
     * Returns url of the referer
     *
     * @return string|null
     */
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
