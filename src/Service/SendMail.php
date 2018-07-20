<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 20/07/18
 * Time: 09:44
 */

namespace App\Service;


use App\Entity\Person;
use Twig\Environment;

class SendMail
{

    private $swift;

    private $twig;


    public function __construct(\Swift_Mailer $swift_Mailer , Environment $environment)
    {
        $this->swift = $swift_Mailer;

        $this->twig = $environment;
    }



    public function registerConfirm(Person $person) {

        $message = (new \Swift_Message('Confirmation de votre inscription'))
            ->setFrom('no_reply@annonce.com')
            ->setTo($person->getEmail())
            ->setBody(
                $this->twig->render('emails/resgisterComfirm.html.twig' , ['person' => $person]),
                'text/html'
            );


        $this->swift->send($message);
    }

}