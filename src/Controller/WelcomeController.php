<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * @Route("/", name="welcome")
     */
    public function index()
    {
        $person = $this->getUser();

        return $this->render('welcome/index.html.twig', [
            'controller_name' => 'WelcomeController',
            'person' => $person,

        ]);
    }
}
