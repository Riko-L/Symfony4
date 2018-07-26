<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 26/07/18
 * Time: 11:17
 */

namespace App\Controller\Api;


use App\Entity\Ads;
use App\Entity\Category;
use App\Repository\AdsRepository;
use App\Repository\CategoryRepository;
use App\Repository\RegionRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class AdsController
 * @package App\Controller\Api
 * @Route("/api")
 */
class AdsController extends Controller
{

    /**
     * @param AdsRepository $adsRepository
     * @return Response
     * @Route("/ads" , name="ads_api"  ,methods="GET")
     */
    public function getAllAds(AdsRepository $adsRepository): Response
    {

        $ads = $adsRepository->findAll();

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer(array($normalizer), array(new JsonEncoder()));

        $arrayContent = $serializer->normalize($ads,null, ['groups' => ['default']]);
        $jsonContent = $serializer->serialize($arrayContent , 'json');


        $response =  new Response();
        $response->setContent($jsonContent);
        $response->headers->set('Content-Type' , 'application /json');

        return  $response;

    }


    /**
     * @param AdsRepository $adsRepository
     * @return Response
     * @Route("/ads/{id}" , name="adsOne_api" , methods="GET")
     */
    public function getOneAds(Ads $ads): Response
    {

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer(array($normalizer), array(new JsonEncoder()));

        $arrayContent = $serializer->normalize($ads,null, ['groups' => ['default']]);

        $jsonContent = $serializer->serialize($arrayContent , 'json');


        $response =  new Response();
        $response->setContent($jsonContent);
        $response->headers->set('Content-Type' , 'application /json');

        return  $response;

    }


    /**
     * @param AdsRepository $adsRepository
     * @return Response
     * @Route("/search" , name="searchAds_api",methods="GET")
     */
    public function getAdsBySearch(CategoryRepository $categoryRepository , RegionRepository $regionRepository,AdsRepository $adsRepository ,Request $request): Response
    {

        $search  = null;
        $region = null;
        $category = null;

        if ($request->query->get('search') != null){
            $search = $request->query->get('search');
        }


        if($request->query->get('category') != null){
            $category = $categoryRepository->findOneBy([ 'name' => $request->query->get('category')]);
            $category = $category->getId();
        }


        if( $request->query->get('region') != null){
            $region = $regionRepository->findOneBy([ 'name' => $request->query->get('region')]);
            $region = $region->getId();
        }

        $search = array(
            'search' => $search,
            'region' => $region,
            'category' => $category
        );


        if($region && $category && $search != null ){
            $ads = $adsRepository->findByCategoryRegionAndKeywrod($search);
        }else{
            $ads = [];
        }


        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer(array($normalizer), array(new JsonEncoder()));

        $arrayContent = $serializer->normalize($ads,null, ['groups' => ['default']]);

        $jsonContent = $serializer->serialize($arrayContent , 'json');


        $response =  new Response();
        $response->setContent($jsonContent);
        $response->headers->set('Content-Type' , 'application /json');

        return  $response;

    }


    /**
     * @param AdsRepository $adsRepository
     * @return Response
     * @Route("/ads/{id}" , name="adsDeleteOne_api" ,methods="DELETE")
     */
    public function deleteOneAds(Ads $ads, EntityManagerInterface $entityManager ): Response
    {

        $entityManager->remove($ads);
        $entityManager->flush();

        $response =  new Response();
        $response->setContent(json_encode(array('Status' => 'Ok')));
        $response->headers->set('Content-Type' , 'application /json');

        return  $response;

    }







}