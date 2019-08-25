<?php

namespace App\Controller;

use App\Entity\ORM\Search;
use App\Form\SearchType;
use App\Repository\CleanerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/find", name="search_find")
     */
    public function find(Request $request, CleanerRepository $cleanerRepository)
    {
        $search = new Search();
        $searchForm = $this->createForm(SearchType::class,$search,[
            'action' => $this->redirectToRoute('search_find'),
        ]);
        $searchForm->handleRequest($request);
        $cleaners=[];
        if ($searchForm->isSubmitted() && $searchForm->isValid()){
            $cleaners= $cleanerRepository->findByDiff($search->getString(),$search->getCategory()->getId(),$search->getMaxPrice(),$search->getMinPrice());
        }
        return $this->render('search_controller/index.html.twig', [
            'cleaners' => $cleaners,
        ]);
    }
}
