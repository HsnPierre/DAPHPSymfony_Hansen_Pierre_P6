<?php
// src/Controller/ProfileController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * Page de profil
     *
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }
}