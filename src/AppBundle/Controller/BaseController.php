<?php

namespace AppBundle\Controller;

use AppBundle\Helper\PaginationHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
     * Returns helper for functionality related to pagination
     *
     * @return PaginationHelper
     */
    protected function getPaginationHelper()
    {
        return $this->getService('app.helper.pagination');
    }
}
