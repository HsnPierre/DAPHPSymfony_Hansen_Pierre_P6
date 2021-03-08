<?php
// src/Controller/RegisterController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * Page d'enregistrement
     *
     * @Route("/register", name="register")
     */
    public function index()
    {
        return $this->render('register/index.html.twig');
    }
}