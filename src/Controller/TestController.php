<?php
// src/Controller/TestController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class TestController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * @Route("/test", name="test")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();

        if(null !== $request->request->get('pic')) {
            $credentials = [
                'pic' => $request->request->get('pic'),
            ];

            $user->setProfilePic($credentials['pic']);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('test');
        }

        return $this->render('test/index.html.twig');
    }

}