<?php
// src/Controller/TrickController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * Page d'un trick
     *
     * @Route("/trick", name="trick")
     */
    public function index()
    {
        return $this->render('trick/index.html.twig');
    }
}