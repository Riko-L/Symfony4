<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 26/07/18
 * Time: 16:04
 */

namespace App\Controller\Api;


use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * Class AccountController
 * @package App\Controller\Api
 * @Route("/api")
 */
class AccountController extends Controller
{


    /**
     * @Route("/user" ,name="register_api", methods="POST")
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder) {

        $person = json_encode($request->request->all());

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array($normalizer), array(new JsonEncoder()));

        $person = $serializer->deserialize($person ,Person::class ,'json');

        $password = $passwordEncoder->encodePassword($person, $person->getPlainPassword());
        $person->setPassword($password);

        $entityManager->persist($person);
        $entityManager->flush();

        $response =  new Response();
        $response->setContent(json_encode(array('Status' => 'Ok')));
        $response->headers->set('Content-Type' , 'application /json');

        return  $response;

    }

}