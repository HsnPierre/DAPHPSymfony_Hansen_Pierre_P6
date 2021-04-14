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
use App\Entity\Trick;
use App\Entity\Comment;

class ProfileController extends AbstractController
{
    /**
     * Page de profil
     *
     * @Route("/profile", name="profile")
     */
    public function index(Request $request)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security_login');
        }

        $repository = $this->getDoctrine()->getRepository(Trick::class);
        $user = $this->getUser();
        $tricks = $repository->findBy(array('author'=>$user->getId()));

        if(null !== $request->request->get('pic')) {
            $credentials = [
                'pic' => $request->request->get('pic'),
            ];

            $user->setProfilePic($credentials['pic']);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig', [
            'tricks' => $tricks,
            ]
        );
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
    public function thisProfile(String $username, Request $request)
    {
        $user_repository = $this->getDoctrine()->getRepository(User::class);
        $trick_repository = $this->getDoctrine()->getRepository(Trick::class);       

        if($user_repository->findOneBy(['username' => $username])){

            $user = $user_repository->findOneBy(['username' => $username]);
            $tricks = $trick_repository->findBy(array('author'=>$user->getId()));

            if( null !== $request->request->get('delete') ){
                $this->deleteUser($username);
            }

            return $this->render('profile/username.html.twig', [
                    'user' => $user,
                    'tricks' => $tricks,
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

    public function deleteUser(string $username)
    {
        $user_repository = $this->getDoctrine()->getRepository(User::class);
        $trick_repository = $this->getDoctrine()->getRepository(Trick::class); 
        $comment_repository = $this->getDoctrine()->getRepository(Comment::class);

        $user = $user_repository->findOneBy(['username' => $username]);

        $comments = $comment_repository->findBy(array('author'=>$user->getId()));
        $tricks = $trick_repository->findBy(array('author'=>$user->getId()));

        $em = $this->getDoctrine()->getManager();

        if($comments !== null){

            foreach($comments as $comment){
                $em->remove($comment);
                $em->flush();
            }

        }

        if($tricks !== null){

            foreach($tricks as $trick){
                $em->remove($trick);
                $em->flush();
            }

        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('home');        
        
    }
}