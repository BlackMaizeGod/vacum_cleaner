<?php

namespace App\Controller;

use App\Entity\Cleaner;
use App\Entity\ORM\Search;
use App\Form\SearchType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use App\Form\CleanerType;
use App\Repository\CleanerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class CleanerController extends AbstractController
{
    /**
     * @Route("/", name="cleaner_index", methods={"GET"})
     */
    public function index(CleanerRepository $cleanerRepository): Response
    {
        $pager = $this->setPager($cleanerRepository->findForPager(), 5);
        $search = new Search();
        $searchForm = $this->createForm(SearchType::class,$search,[
            'action' => $this->redirectToRoute('search_find'),
        ]);
        $cleanersArray=[];
        foreach ($pager as $item){
            $cleanersArray[]=$item;
        }
        return $this->render('cleaner/index.html.twig', [
            'cleaners' => $cleanersArray,
            'pager' => $pager,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/new", name="cleaner_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cleaner = new Cleaner();
        $form = $this->createForm(CleanerType::class, $cleaner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cleaner);
            $entityManager->flush();
            if (!is_null($form->getData()->getAvatar())) {
                move_uploaded_file($request->files->get('cleaner')['avatar']->getPathName(),
                    'upload/' . $cleaner->getId());
                $cleaner->setAvatar('upload/' . $cleaner->getId());
            }
            $entityManager->persist($cleaner);
            $entityManager->flush();

            return $this->redirectToRoute('cleaner_index');
        }

        return $this->render('cleaner/new.html.twig', [
            'cleaner' => $cleaner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cleaner_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cleaner $cleaner): Response
    {
        $avatar=$cleaner->getAvatar();
        $form = $this->createForm(CleanerType::class, $cleaner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!is_null($form->getData()->getAvatar())) {
                move_uploaded_file(
                    $request->files->get('cleaner')['avatar']->getPathName(),
                    'upload/' . $cleaner->getId()
                );
                $cleaner->setAvatar('upload/' . $cleaner->getId());
            }else {
                $cleaner->setAvatar($avatar);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cleaner_index');
        }

        return $this->render('cleaner/edit.html.twig', [
            'cleaner' => $cleaner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cleaner_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cleaner $cleaner): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cleaner->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cleaner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cleaner_index');
    }

    protected function setPager($repository, $maxPerPage)
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($repository));
        $pager->setMaxPerPage($maxPerPage);
        if (!empty($_GET['page'])) {
            $pager->setCurrentPage($_GET['page']);
        }

        return $pager;
    }
}
