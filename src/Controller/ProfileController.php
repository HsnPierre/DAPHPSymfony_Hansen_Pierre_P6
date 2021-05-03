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
use App\Entity\Media;

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

        if(null !== $request->request->get('delete') && $request->request->get('checkbox') !== false){
            $trick_repository = $this->getDoctrine()->getRepository(Trick::class); 
            $comment_repository = $this->getDoctrine()->getRepository(Comment::class);
            $media_repository = $this->getDoctrine()->getRepository(Media::class);

            $user = $this->getUser();

            $user_comments = $comment_repository->findBy(array('author'=>$user->getId()));
            $tricks = $trick_repository->findBy(array('author'=>$user->getId()));

            $medias = [];

            foreach($tricks as $trick){
                $medias[] = $media_repository->findBy(array('trick'=>$trick->getId()));
                $trick_comments[] = $comment_repository->findBy(array('trick'=>$trick->getId()));
            }

            $em = $this->getDoctrine()->getManager();

            if($medias[0] !== null){

                foreach($medias as $media){
                    foreach($media as $med){
                        if($med->getType() == 'image') {
                            unlink('assets/img/trick/post/medias/'.$med->getName());
                            unlink('assets/img/original/'.$med->getName());   
                        }
                        $em->remove($med);
                        $em->flush();
                    }
                }
                
            }

            if($trick_comments[0] !== null){

                foreach($trick_comments as $comment){
                    foreach($comment as $com){
                        $em->remove($com);
                        $em->flush();
                    }
                }
                
            }

            if($user_comments !== null){

                foreach($user_comments as $comment){
                    $em->remove($comment);
                    $em->flush();
                }

            }

            if($tricks !== null){

                foreach($tricks as $trick){
                    unlink('assets/img/trick/post/'.$trick->getMainpic());
                    unlink('assets/img/trick/thumbnails/'.$trick->getMainpic());
                    $em->remove($trick);
                    $em->flush();
                }

            }

            $em->remove($user);
            $em->flush();

            return $this->redirectToRoute('security_logout');  
        } elseif(null !== $request->request->get('delete') && $request->request->get('checkbox') == false){
            $this->addFlash('error', 'Veuillez cocher la case pour supprimer votre compte.');
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
        $public = [];

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $array = $request->request->get('profile');

            if(isset($array['public_name'])){
                $public[0] = 'name';
            } else {
                $public[0] = '0';
            }
            if(isset($array['public_surname'])){
                $public[1] = 'surname';
            } else {
                $public[1] = '0';
            }
            if(isset($array['public_username'])){
                $public[2] = 'username';
            } else {
                $public[2] = '0';
            }
            if(isset($array['public_mail'])){
                $public[3] = 'mail';
            } else {
                $public[3] = '0';
            }

            $user->setPublic($public);
            
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

            if(null !== $request->request->get('delete') && $request->request->get('checkbox') !== false){
                $user_repository = $this->getDoctrine()->getRepository(User::class);
                $trick_repository = $this->getDoctrine()->getRepository(Trick::class); 
                $comment_repository = $this->getDoctrine()->getRepository(Comment::class);
                $media_repository = $this->getDoctrine()->getRepository(Media::class);

                $user = $user_repository->findOneBy(['username' => $username]);

                $user_comments = $comment_repository->findBy(array('author'=>$user->getId()));
                $tricks = $trick_repository->findBy(array('author'=>$user->getId()));

                $medias = [];

                foreach($tricks as $trick){
                    $medias[] = $media_repository->findBy(array('trick'=>$trick->getId()));
                    $trick_comments[] = $comment_repository->findBy(array('trick'=>$trick->getId()));
                }

                $em = $this->getDoctrine()->getManager();

                if($medias[0] !== null){

                    foreach($medias as $media){
                        foreach($media as $med){
                            if($med->getType() == 'image') {
                                unlink('assets/img/trick/post/medias/'.$med->getName());
                                unlink('assets/img/original/'.$med->getName());   
                            }
                            $em->remove($med);
                            $em->flush();
                        }
                    }
                    
                }

                if($trick_comments[0] !== null){

                    foreach($trick_comments as $comment){
                        foreach($comment as $com){
                            $em->remove($com);
                            $em->flush();
                        }
                    }
                    
                }

                if($user_comments !== null){

                    foreach($user_comments as $comment){
                        $em->remove($comment);
                        $em->flush();
                    }

                }

                if($tricks !== null){

                    foreach($tricks as $trick){
                        unlink('assets/img/trick/post/'.$trick->getMainpic());
                        unlink('assets/img/trick/thumbnails/'.$trick->getMainpic());
                        $em->remove($trick);
                        $em->flush();
                    }

                }

                $em->remove($user);
                $em->flush();

                return $this->redirectToRoute('home');
            } elseif(null !== $request->request->get('delete') && $request->request->get('checkbox') == false){
                $this->addFlash('error', 'Veuillez cocher la case pour supprimer ce compte.');
            }

            return $this->render('profile/username.html.twig', [
                    'user' => $user,
                    'tricks' => $tricks,
                    'error' => null
                ]
            );

        }
    }
}