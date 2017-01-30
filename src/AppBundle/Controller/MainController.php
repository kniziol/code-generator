<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides main, application-wide functionality
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class MainController extends BaseController
{
    /**
     * Displays homepage
     *
     * @param Request $request The request
     * @return array
     *
     * @Route(
     *     "/",
     *     name="app.homepage"
     * )
     *
     * @Method("GET")
     * @Template()
     */
    public function homepageAction(Request $request)
    {
        return [];
    }
}
