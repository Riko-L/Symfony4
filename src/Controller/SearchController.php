<?php

namespace App\Controller;

use App\Repository\AdsRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search" , methods="POST")
     */
    public function search(Request $request, AdsRepository $adsRepository)
    {
        $search = $request->request->get('search');

        $ads = $adsRepository->findByCategoryRegionAndKeywrod($search);

        return $this->render('search/index.html.twig' , [
            'ads' => $ads
        ]);
    }
}
