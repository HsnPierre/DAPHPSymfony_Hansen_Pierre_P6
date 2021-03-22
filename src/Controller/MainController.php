<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * Page d'accès à un trick
     * 
     * @Route(
     * "/trick/{trickId<\d+>}",
     * name="trick",
     * methods={"GET"}
     * )
     */
    public function showTrick($trickId)
    {

    }
}