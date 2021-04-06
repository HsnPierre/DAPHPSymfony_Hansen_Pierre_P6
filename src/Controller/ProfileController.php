<?php
// src/Controller/ProfileController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\ProfileType;
use App\Form\ProfilepType;
use App\Entity\User;

class ProfileController extends AbstractController
{
    /**
     * Page de profil
     *
     * @Route("/profile", name="profile")
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

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig');
    }
    
    /**
     * Modifier le profil
     * 
     * @Route("/profile/edit", name="edit_profile")
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();
        $user->setPlainPassword('&1Azertyuiop');

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            $user->setPlainPassword(null);

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', array(
            'profileform' => $form->createView(),
        ));
    }

    /**
     * Modifier le mot de passe
     * 
     * @Route("/profile/edit-2", name="edit_password")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $error = null;

        $form = $this->createForm(ProfilepType::class, $user);

        $form->handleRequest($request);

        if(null !== $request->request->get('password1')) {
            $credentials = [
                'old' => $request->request->get('password1'),
                'new' => $request->request->get('password2'),
                'new2' => $request->request->get('password3'),
            ];

            if($passwordEncoder->isPasswordValid($user, $credentials['old']) && $form->isSubmitted() && $form->isValid()) {

                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('profile');
            } else {
                $error = 'Le mot de passe est incorrect';
            }
        }

        return $this->render('profile/password.html.twig', [
            'error' => $error,
            'passwordform' => $form->createView(),
            ]
        );
    }

    /**
     * Page de profil d'un utilisateur donné
     *
     * @Route("/profile/{username}", name="this_user_profile")
     */
    public function thisProfile(String $username)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);

        if($repository->findOneBy(['username' => $username])){

            $user = $repository->findOneBy(['username' => $username]);

            return $this->render('profile/username.html.twig', [
                    'user' => $user,
                    'error' => null
                ]
            );
        } else {
            $error = "Cet utilisateur n'existe pas.";

            return $this->render('profile/username.html.twig', [
                'error' => $error
            ]
        );
        }
    }

    public function delete()
    {
        
    }
}