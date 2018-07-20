<?php
/**
 * Created by PhpStorm.
 * User: ledev
 * Date: 17/07/2018
 * Time: 15:38
 */

namespace App\Controller;


use App\Entity\Person;
use App\Form\PersonType;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{


    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));

    }


    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, SendMail $sendMail, EntityManagerInterface $entityManager)
    {
        // 1) build the form
        $user = new Person();
        $form = $this->createForm(PersonType::class, $user)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ));;

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager->persist($user);
            $entityManager->flush();


            $sendMail->registerConfirm($user);

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user


            return $this->redirectToRoute('welcome');
        }

        return $this->render(
            'security/register.html.twig',
            array('form' => $form->createView())
        );
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}