<?php
// src/Controller/RegisterController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;
use App\Entity\User;

class RegisterController extends AbstractController
{
    /**
     * Page d'enregistrement
     *
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $user = new User();
        
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dump($user);
        }

        return $this->render('register/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}