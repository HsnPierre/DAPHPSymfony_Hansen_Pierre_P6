<?php
// src/Controller/RegisterController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\RegisterType;
use App\Entity\User;

class RegisterController extends AbstractController
{
    /**
     * Page d'enregistrement
     *
     * @Route("/register", name="user.register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $erreur = null;
        
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $request->request->get('rgpd') !== 0) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('security_login');

        } else if($form->isSubmitted() && $request->request->get('rgpd') == 0) {
            $erreur = 'Vous devez accepter les conditions pour vous inscrire.';
        }

        return $this->render('register/index.html.twig', array(
            'registerform' => $form->createView(),
            'erreur' => $erreur
        ));
    }
}