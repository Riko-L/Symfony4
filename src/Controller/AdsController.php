<?php

namespace App\Controller;

use App\Entity\Ads;
use App\Form\AdsType;
use App\Repository\AdsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



/**
 * Class AccountController
 * @package App\Controller
 * @Route("/ads")
 * @IsGranted("ROLE_USER")
 */
class AdsController extends Controller
{
    /**
     * @Route("", name="ads")
     */
    public function index(AdsRepository $adsRepository)
    {
        $person = $this->getUser();
        return $this->render('ads/index.html.twig', [
            'ads' => $adsRepository->findAll(),
            'person' => $person
        ]);
    }


    /**
     * @Route("/new", name="ads_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $ads = new Ads();

        $person = $this->getUser();
        $ads->setAuthor($person);

        $form = $this->createForm(AdsType::class, $ads);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ads);
            $em->flush();

            return $this->redirectToRoute('account_show');
        }

        return $this->render('ads/new.html.twig', [
            'ads' => $ads,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

}
