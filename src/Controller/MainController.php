<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends BaseController
{
    /**
     * @Route(
     *     "/{_locale}",
     *     name="app.homepage",
     *     methods={"GET"},
     *     requirements={"_locale": "%available_locales%"},
     *     defaults={"_locale": "%locale%"}
     * )
     *
     * @Template()
     */
    public function homepage(): array
    {
        return [];
    }
}
