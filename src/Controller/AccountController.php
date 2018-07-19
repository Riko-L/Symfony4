<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 18/07/18
 * Time: 11:09
 */

namespace App\Controller;
use App\Entity\Person;
use App\Form\PersonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 * @package App\Controller
 * @Route("/account")
 * @IsGranted("ROLE_USER")
 */
class AccountController extends Controller
{

    /**
     * @Route("", name="account_show", methods="GET")
     */
    public function show(): Response
    {
        return $this->render('account/show.html.twig', ['person' => $this->getUser()]);
    }



    /**
     * @Route("/edit", name="account_edit", methods="GET|POST")
     */
    public function edit(Request $request): Response
    {
        $person = $this->getUser();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_show');
        }

        return $this->render('account/edit.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("", name="account_delete", methods="DELETE")
     */
    public function delete(Request $request): Response
    {

        $person = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();

            $person->setIsDelete(true);

            $em->flush();

        }

        return $this->redirectToRoute('logout');
    }


}