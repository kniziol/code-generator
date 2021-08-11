<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

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
     * @return array
     *
     * @Route(
     *     "/",
     *     name="app.homepage",
     *     methods={"GET"}
     * )
     *
     * @Template()
     */
    public function homepage(): array
    {
        return [];
    }
}
