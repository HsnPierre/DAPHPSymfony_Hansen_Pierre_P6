<?php
// src/Controller/TrickController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Media;
use App\Form\TrickType;
use App\Form\CommentType;

class TrickController extends AbstractController
{
    
    /**
     * Page d'ajout d'un trick
     *
     * @Route("/trick/add", name="add_trick")
     */
    public function add(Request $request)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security_login');
        }

        $trick = new Trick();
        $user = $this->getUser();
        
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $trick->setAuthor($user);

            $medias = $form->get('medias')->getData();
            
            $ext = exif_imagetype($form->get('mainpic')->getData());

            if($ext == 3){
                $post_pic = imagecreatefrompng($form->get('mainpic')->getData());
                $fs_post_pic = imagescale($post_pic, 1120, 560);
                $thumbnail_post_pic = imagescale($post_pic, 650, 350);
            } elseif($ext == 2){
                $post_pic = imagecreatefromjpeg($form->get('mainpic')->getData());
                $fs_post_pic = imagescale($post_pic, 1120, 560);
                $thumbnail_post_pic = imagescale($post_pic, 650, 350);
            } else {
                $erreur = "Le format d'image n'est pas supporté.";

                return $this->render('trick/add.html.twig', [
                    'trickform' => $form->createView(),
                    'erreur' => $erreur
                    ]
                );
            }

            if ($fs_post_pic !== false && $thumbnail_post_pic !== false) {
                if($ext == 3){
                    $post_pic_name = md5(uniqid()).'.png';
                    imagepng($fs_post_pic, 'assets/img/trick/post/'.$post_pic_name);
                    imagepng($thumbnail_post_pic, 'assets/img/trick/thumbnails/'.$post_pic_name);
                } elseif($ext == 2){
                    $post_pic_name = md5(uniqid()).'.jpg';
                    imagejpeg($fs_post_pic, 'assets/img/trick/post/'.$post_pic_name);
                    imagejpeg($thumbnail_post_pic, 'assets/img/trick/thumbnails/'.$post_pic_name);
                }

                $trick->setMainpic($post_pic_name);
            }
 
            foreach($medias as $media){

                $originalFileExt = pathinfo($media->getClientOriginalName(), PATHINFO_EXTENSION);

                if($originalFileExt == 'jpg' || $originalFileExt == 'jpeg') {
                    $media_pic = imagecreatefromjpeg($media);
                    $resize = imagescale($media_pic, 450, 150);

                    $media_pic_name = md5(uniqid()).'.'.$originalFileExt;
                    imagejpeg($resize, 'assets/img/trick/post/medias/'.$media_pic_name);
                    imagejpeg($media_pic, 'assets/img/original/'.$media_pic_name);
                } else if($originalFileExt == 'png') {
                    $media_pic = imagecreatefrompng($media);
                    $resize = imagescale($media_pic, 450, 150);

                    $media_pic_name = md5(uniqid()).'.'.$originalFileExt;
                    imagepng($resize, 'assets/img/trick/post/medias/'.$media_pic_name);
                    imagepng($media_pic, 'assets/img/original/'.$media_pic_name);
                }

                $med = new Media();
                $med->setName($media_pic_name);
                $trick->addMedia($med);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('trick/add.html.twig', [
            'trickform' => $form->createView(),
            ]
        );
    }

    /**
     * Page d'un trick
     *
     * @Route("/trick/{slug}", name="show_trick")
     */
    public function index(String $slug, Request $request)
    {
        $trick_repository = $this->getDoctrine()->getRepository(Trick::class);
        $comment_repository = $this->getDoctrine()->getRepository(Comment::class);

        $trick = $trick_repository->findOneBy(['name' => $slug]);

        $user = $this->getUser();
        $comment = new Comment();

        if($trick){
        
            if($this->getUser()){
                
                $form = $this->createForm(CommentType::class, $comment);
                $form->handleRequest($request);                  

                if($form->isSubmitted() && $form->isValid()){
    
                    $comment->setAuthor($user);
                    $comment->setTrick($trick);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comment);
                    $em->flush();
        
                }

                return $this->render('trick/index.html.twig', [
                        'trick' => $trick,
                        'commentform' => $form->createView(),
                    ]
                );
            }

            return $this->render('trick/index.html.twig', [
                    'trick' => $trick,
                ]
            );
        }
    }

    /**
     * Page d'édition d'un trick
     *
     * @Route("/trick/{slug}/edit", name="edit_trick")
     */
    public function edit(Request $request, String $slug)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security_login');
        }

        $repository = $this->getDoctrine()->getRepository(Trick::class);

        $trick = $repository->findOneBy(['name' => $slug]);
        $user = $this->getUser();
        $date = new \Datetime();
        $pic = $trick->getMainpic();
        $mainpic = 'assets/img/trick/post/'.$trick->getMainpic();
        
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $trick->setEditor($user);
            $trick->setDateedit($date);

            if(null === $request->request->get('mainpic')){
                $trick->setMainpic($pic);
            }
        }

        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('trick/edit.html.twig', [
            'trickform' => $form->createView(),
            'trick' => $trick,
            'mainpic' => $mainpic
            ]
        ); 
    }

    public function delete()
    {

    }
}