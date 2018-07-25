<?php

namespace App\Controller;

use App\Entity\Ads;
use App\Form\AdsType;
use App\Form\SearchType;
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
 *
 */
class AdsController extends Controller
{
    /**
     * @Route("", name="ads")
     */
    public function index(AdsRepository $adsRepository)
    {
        $person = $this->getUser();
        $ads = $adsRepository->findAll();


        $formSearch = $this->createForm(SearchType::class);



        $inactiveAds = array_filter(
            array_map(function(Ads $ad) {
                return !$ad->getIsActive();
            }, $ads)
        );

        return $this->render('ads/index.html.twig', [
            'ads' => $ads,
            'person' => $person,
            'inactiveAds' => $inactiveAds,
            'formSearch' => $formSearch->createView(),
        ]);
    }

    /**
     *@Route("/show/{ad}", name="ads_show")
     */
    public function show(Ads $ad) {
        $person = $this->getUser();

        $photos = $ad->getPhotos();

        return $this->render('ads/show.html.twig' , ['ad'=> $ad , 'person' => $person , 'photos' => $photos]);
    }

    /**
     * @Route("/new", name="ads_new", methods="GET|POST")
     *
     *
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


    /**
     * @Route("/{ads}", name="ads_delete", methods="DELETE")
     *
     */
    public function delete(Request $request, Ads $ads): Response
    {

        if ($this->isCsrfTokenValid('delete'.$ads->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ads);
            $em->flush();
        }

        return $this->redirectToRoute('account_show');
    }


    /**
     * @Route("/{ads}/edit", name="ads_edit", methods="GET|POST")
     *
     */
    public function edit(Request $request, Ads $ads): Response
    {
        $person = $this->getUser();

        $photos = $ads->getPhotos();

        $form = $this->createForm(AdsType::class, $ads);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_show', ['ads' => $ads->getId()]);
        }

        return $this->render('ads/edit.html.twig', [
            'ads' => $ads,
            'photos' => $photos,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Ads $ad
     * @Route("/{ad}/moderate" , name="ads_moderate")
     */
    public function moderate(Ads $ad) {


        $ad->setIsActive(!$ad->getIsActive());
        $this->getDoctrine()->getManager()->flush();


        return $this->redirectToRoute('ads');
    }

}
