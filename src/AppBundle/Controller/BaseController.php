<?php

namespace AppBundle\Controller;

use AppBundle\Helper\PaginationHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Base controller with common functionaliy
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
abstract class BaseController extends Controller
{
    /**
     * Returns the repository for given entity
     *
     * @param string $entityClassNamespace Namespace of entity (bundle-style or full namespace)
     * @return EntityRepository
     */
    protected function getRepository($entityClassNamespace)
    {
        return $this->getService('doctrine')->getRepository($entityClassNamespace);
    }

    /**
     * Returns service by given name / ID stored in the container
     *
     * @param string  $serviceName     Name / ID of the service
     * @param integer $invalidBehavior (optional) Behavior when the service does not exist. One of the ContainerInterface class constants of integer type.
     * @return mixed
     */
    protected function getService($serviceName, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {
        return $this->container->get($serviceName, $invalidBehavior);
    }

    /**
     * Returns the Doctrine's entity manager responsible for working with queries, entities etc.
     *
     * @return EntityManager
     */
    protected function getDoctrineManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    /**
     * Returns helper for functionality related to pagination
     *
     * @return PaginationHelper
     */
    protected function getPaginationHelper()
    {
        return $this->getService('app.helper.pagination');
    }

    /**
     * Redirects to the referer or (if empty and not exists) to url based on given route and parameters
     *
     * @param string $routeName       (optional) Name of the route which is used to redirect when the referer url is empty / unknown
     * @param array  $routeParameters (optional) An array of parameters for given route
     * @return RedirectResponse
     */
    protected function redirectToReferer($routeName = '', $routeParameters = [])
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
    protected function getRefererUrl()
    {
        return $this
            ->getService('request_stack')
            ->getCurrentRequest()
            ->headers
            ->get('referer');
    }

    /**
     * Returns the "translator" service
     *
     * @return TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->getService('translator');
    }
}
