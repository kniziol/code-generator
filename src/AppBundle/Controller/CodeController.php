<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Code;
use AppBundle\Repository\CodeRepository;
use Doctrine\ORM\Query;
use Faker\Factory;
use Faker\Provider\Barcode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides code-related functionality
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 *
 * @Route("/codes")
 */
class CodeController extends BaseController
{
    /**
     * Adds / generates random codes
     *
     * @param Request $request The request
     * @return RedirectResponse
     *
     * @Route(
     *     "/add-random-codes",
     *     name="app.code.add_random"
     * )
     *
     * @Method("GET")
     */
    public function addRandomAction(Request $request)
    {
        $maxCodesCount = 10;
        $now = new \DateTime();

        $generator = new Barcode(Factory::create());
        $entityManager = $this->getDoctrineManager();
        $generatedCodes = $this->getCodeRepository()->getCodesValues();

        for ($i = 1; $i <= $maxCodesCount;) {
            $value = $generator->isbn10();

            /*
             * The code is not unique (was already generated)?
             * Let's generate another
             */
            if (in_array($value, $generatedCodes)) {
                continue;
            }

            $code = new Code();
            $code->setValue($value);
            $code->setCreatedAt($now);

            $entityManager->persist($code);
            $i++;
        }

        $entityManager->flush();

        $message = $this->getTranslator()->trans('flash.code.random_codes_added', [], 'AppBundle');
        $this->addFlash('success', $message);

        return $this->redirectToReferer('app.code.index');
    }

    /**
     * Returns repository for the Code entity
     *
     * @return CodeRepository
     */
    private function getCodeRepository()
    {
        /* @var $repository CodeRepository */
        $repository = $this->getRepository(Code::class);

        return $repository;
    }

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
     *          "page": "^[1-9][0-9]*$"
     *     },
     *     defaults={
     *          "page": 1
     *     }
     * )
     *
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, $page = 1)
    {
        $page = (int)$page;
        $repository = $this->getCodeRepository();
        $paginationHelper = $this->getPaginationHelper();

        $allCodesCount = (int)$repository
            ->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);

        $perPage = $paginationHelper->getPaginationPerPageValue();
        $offset = $paginationHelper->getPaginationOffsetValue($page);
        $pagesCount = $paginationHelper->getPagesCount($allCodesCount);

        /*
         * Oops, requested number of page for pagination is not the default / 1st page and is out of scope
         */
        if ($page !== 1 && $page > $pagesCount) {
            throw $this->createNotFoundException();
        }

        $orderBy = [
            'createdAt' => 'ASC',
        ];

        /*
         * Prepare codes and data for template / view
         */
        $criteria = [];
        $codes = $repository->findBy($criteria, $orderBy, $perPage, $offset);

        $nextPageNumber = $paginationHelper->getPaginationPageNumber($page, $pagesCount);
        $previousPageNumber = $paginationHelper->getPaginationPageNumber($page, $pagesCount, false);

        return [
            'codes'              => $codes,
            'offset'             => $offset,
            'pagesCount'         => $pagesCount,
            'allCodesCount'      => $allCodesCount,
            'nextPageNumber'     => $nextPageNumber,
            'previousPageNumber' => $previousPageNumber,
        ];
    }
}
