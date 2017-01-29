<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides code-related functionality
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class CodeController extends Controller
{
    /**
     * Displays all codes
     *
     * @param Request $request The request
     * @param int     $page    (optional) Number of page. Used for pagination.
     * @return array
     *
     * @Route(
     *     "/{page}",
     *     name="app.code.index",
     *     requirements={
     *          "page": "\d+"
     *     },
     *     defaults={
     *          "page": 1
     *     }
     * )
     * @Template()
     */
    public function indexAction(Request $request, $page = 1)
    {
    }
}
