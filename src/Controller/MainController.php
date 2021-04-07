<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Trick;
use App\Entity\User;

class MainController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * @Route("/", name="home")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Trick::class);
        $tricks = $repository->findBy(array(), array('date'=>'ASC'));


        return $this->render('main/index.html.twig', [
            'tricks' => $tricks,
        ]
    );
    }
}