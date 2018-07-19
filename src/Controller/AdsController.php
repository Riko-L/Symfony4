<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdsController extends Controller
{
    /**
     * @Route("/ads", name="ads")
     */
    public function index()
    {
        return $this->render('ads/index.html.twig', [
            'controller_name' => 'AdsController',
        ]);
    }
}
