<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends BaseController
{
    /**
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
