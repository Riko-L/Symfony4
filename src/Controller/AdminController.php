<?php
/**
 * Created by PhpStorm.
 * User: ledev
 * Date: 17/07/2018
 * Time: 15:23
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
   /**
     * @Route("/admin", name="admin_welcome")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}