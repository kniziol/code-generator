<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Code;
use App\Form\CodeDeleteType;
use App\Form\CodeType;
use App\Helper\PaginationHelper;
use App\Repository\CodeRepository;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Faker\Factory;
use Faker\Provider\Barcode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface    $translator
     * @param CodeRepository         $codeRepository
     * @return RedirectResponse
     *
     * @Route(
     *     "/add-random-codes",
     *     name="app.code.add_random",
     *     methods={"GET"}
     * )
     */
    public function addRandom(
        EntityManagerInterface $entityManager,
        TranslatorInterface    $translator,
        CodeRepository         $codeRepository
    ):
    RedirectResponse {
        $maxCodesCount = 10;
        $now = new DateTime();

        $generator = new Barcode(Factory::create());
        $generatedCodes = $codeRepository->getCodesValues();

        for ($i = 1; $i <= $maxCodesCount;) {
            $value = $generator->isbn10();

            /*
             * The code is not unique (was already generated)?
             * Let's generate another
             */
            if (in_array($value, $generatedCodes, true)) {
                continue;
            }

            $code = new Code();
            $code->setValue($value);
            $code->setCreatedAt($now);

            try {
                $entityManager->persist($code);
            } catch (ORMException $e) {
            }

            $i++;
        }

        try {
            $entityManager->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }

        $message = $translator->trans('flash.code.random_codes_added');
        $this->addFlash('success', $message);

        return $this->redirectToReferer('app.code.index');
    }

    /**
     * Adds 1 code provided by user
     *
     * @param Request                $request The request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface    $translator
     * @return array|RedirectResponse
     *
     * @Route(
     *     "/add-code",
     *     name="app.code.add",
     *     methods={"GET", "POST"}
     * )
     *
     * @Template()
     */
    public function add(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $now = new DateTime();
        $code = (new Code())->setCreatedAt($now);

        $form = $this->createForm(CodeType::class, $code);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($code);
                $entityManager->flush($code);
            } catch (ORMException $e) {
            }

            $message = $translator->trans('flash.code.your_code_added');
            $this->addFlash('success', $message);

            return $this->redirectToReferer('app.code.add');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays all codes
     *
     * @param PaginationHelper $paginationHelper
     * @param CodeRepository   $codeRepository
     * @param int              $page (optional) Number of page. Used for pagination.
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
     *     },
     *     methods={"GET"}
     * )
     *
     * @Template()
     */
    public function index(PaginationHelper $paginationHelper, CodeRepository $codeRepository, int $page = 1): array
    {
        $allCodesCount = (int)$codeRepository
            ->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

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
        $codes = $codeRepository->findBy($criteria, $orderBy, $perPage, $offset);

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

    /**
     * Deletes codes with given values
     *
     * @param Request                $request The request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface    $translator
     * @param CodeRepository         $codeRepository
     * @return RedirectResponse|array
     *
     * @Route(
     *     "/delete",
     *     name="app.code.delete",
     *     methods={"GET", "POST"}
     * )
     *
     * @Template()
     */
    public function delete(
        Request                $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface    $translator,
        CodeRepository         $codeRepository
    ) {
        $form = $this->createForm(CodeDeleteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*
             * 1st step:
             * Grab the data
             */
            $data = $form->getData();
            $codesString = $data[CodeDeleteType::TEXT_AREA_NAME];

            /*
             * 2nd step:
             * Remove empty lines
             */
            $codesString = preg_replace('/[\n\r]+/', '|', $codesString);

            /*
             * 3rd step:
             * Create an array
             */
            $codesValues = explode('|', $codesString);

            /*
             * 4th step:
             * Fetch and verify codes
             */
            $codes = $codeRepository->getCodesByValues($codesValues);

            if (count($codesValues) !== count($codes)) {
                $message = $translator->trans('flash.code.delete.nonexisting_codes');
                $this->addFlash('danger', $message);
            } else {
                /* @var $code Code */
                foreach ($codes as $code) {
                    try {
                        $entityManager->remove($code);
                        $entityManager->flush();
                    } catch (ORMException $e) {
                    }
                }

                $message = $translator->trans('flash.code.delete.success');
                $this->addFlash('success', $message);

                return $this->redirectToReferer('app.code.index');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
