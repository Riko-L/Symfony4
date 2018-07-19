<?php

namespace App\Controller;

use App\Entity\Ads;
use App\Entity\Photo;
use App\Form\PhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



/**
 * Class AccountController
 * @package App\Controller
 * @Route("/photo")
 * @IsGranted("ROLE_USER")
 */
class PhotoController extends Controller
{
    /**
     * @Route("", name="photo")
     */
    public function index()
    {
        return $this->render('photo/index.html.twig', [
            'controller_name' => 'PhotoController',
        ]);
    }


    /**
     * @Route("/new/{ads}", name="photo_new", methods="GET|POST")
     */
    public function new(Request $request, Ads $ads): Response
    {
        $photo = new Photo();

        $person = $this->getUser();


        $photo->setAds($ads);

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();

            return $this->redirectToRoute('ads_new');
        }

        return $this->render('ads/new.html.twig', [
            'ads' => $ads,
            'photo' => $photo,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }
}
