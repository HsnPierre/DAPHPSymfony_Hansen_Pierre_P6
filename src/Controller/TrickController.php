<?php
// src/Controller/TrickController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
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
        
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $trick->setAuthor($user);

            $medias = $form->get('medias')->getData();
            $ext = exif_imagetype($form->get('mainpic')->getData());
            $delimiter = ";";
            $videos = [];
            $thumbnails = [];

            if(strstr($form->get('video')->getData(), $delimiter) !== false){
                $videos = explode($delimiter, $form->get('video')->getData());
            } else {
                $videos[] = $form->get('video')->getData();
            }

            for($count=0;$count<count($videos);$count++){
                $thumbnails[] = str_replace("https://youtu.be/", '', $videos[$count]);
            }

            if($ext == 3){
                $post_pic = imagecreatefrompng($form->get('mainpic')->getData());
                $fs_post_pic = imagescale($post_pic, 1120, 560);
                $thumbnail_post_pic = imagescale($post_pic, 650, 350);
            } elseif($ext == 2){
                $post_pic = imagecreatefromjpeg($form->get('mainpic')->getData());
                $fs_post_pic = imagescale($post_pic, 1120, 560);
                $thumbnail_post_pic = imagescale($post_pic, 650, 350);
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
                    $tmp = imagescale($media_pic, 320, 180);
                    $resize = imagecrop($tmp, ['x' => 0, 'y' => 0, 'width' => 320, 'height' => 180]);

                    $media_pic_name = md5(uniqid()).'.'.$originalFileExt;
                    imagejpeg($resize, 'assets/img/trick/post/medias/'.$media_pic_name);
                    imagejpeg($media_pic, 'assets/img/original/'.$media_pic_name);
                } else if($originalFileExt == 'png') {
                    $media_pic = imagecreatefrompng($media);
                    $tmp = imagescale($media_pic, 320, 180);
                    $resize = imagecrop($tmp, ['x' => 0, 'y' => 0, 'width' => 320, 'height' => 180]);

                    $media_pic_name = md5(uniqid()).'.'.$originalFileExt;
                    imagepng($resize, 'assets/img/trick/post/medias/'.$media_pic_name);
                    imagepng($media_pic, 'assets/img/original/'.$media_pic_name);
                }

                $med = new Media();
                $med->setName($media_pic_name);
                $med->setType('image');
                $trick->addMedia($med);
            }
            
            if(!empty($videos) && !empty($thumbnails)){

                for($count=0;$count<count($videos);$count++){
                    $med = new Media();
                    $med->setType('video');
                    $med->setName($thumbnails[$count]);
                    $med->setUrl($videos[$count]);
                    $trick->addMedia($med);
                }

            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $this->addFlash('success', 'Votre post a bien été ajouté.');

            return $this->redirectToRoute('home', ['_fragment' => 'tricks']);
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
                
                $posted_comments = $comment_repository->findBy(['trick' => $trick->getId()]);
                
                if($posted_comments !== null){
                    foreach($posted_comments as $posted_comment){
                        if($request->request->get('deletecomment') == $posted_comment->getId()){
                            
                            $em = $this->getDoctrine()->getManager();

                            $em->remove($posted_comment);
                            $em->flush();

                            $this->addFlash('success', 'Le commentaire a bien été supprimé.');

                            return $this->redirectToRoute('show_trick', ['slug' => $slug]);
                        }
                    }
                }


                if($form->isSubmitted() && $form->isValid() && $form->get('rgpd')->getData() !== false){
    
                    $comment->setAuthor($user);
                    $comment->setTrick($trick);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comment);
                    $em->flush();

                    $this->addFlash('success', 'Le commentaire a bien été publié.');

                    return $this->redirectToRoute('show_trick', ['slug' => $slug]);
        
                } elseif($form->isSubmitted() && $form->get('rgpd')->getData() == false) {
                    $this->addFlash('error', 'Vous devez accepter les conditions pour commenter.');
                }

                return $this->render('trick/index.html.twig', [
                        'trick' => $trick,
                        'commentform' => $form->createView(),
                    ]
                );
            }

            return $this->render('trick/index.html.twig', [
                    'trick' => $trick
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
        
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $trick->setEditor($user);
            $trick->setDateedit($date);

            if(null !== $form->get('video')->getData()){
                $delimiter = ";";
                $videos = [];
                $thumbnails = [];
    
                if(strstr($form->get('video')->getData(), $delimiter) !== false){
                    $videos = explode($delimiter, $form->get('video')->getData());
                } else {
                    $videos[] = $form->get('video')->getData();
                }
    
                for($count=0;$count<count($videos);$count++){
                    $thumbnails[] = str_replace("https://youtu.be/", '', $videos[$count]);
                }

                for($count=0;$count<count($videos);$count++){
                    $med = new Media();
                    $med->setType('video');
                    $med->setName($thumbnails[$count]);
                    $med->setUrl($videos[$count]);
                    $trick->addMedia($med);
                }
            }

            if(null !== $form->get('medias')->getData()){
                $medias = $form->get('medias')->getData();

                foreach($medias as $media){

                    $originalFileExt = pathinfo($media->getClientOriginalName(), PATHINFO_EXTENSION);
    
                    if($originalFileExt == 'jpg' || $originalFileExt == 'jpeg') {
                        $media_pic = imagecreatefromjpeg($media);
                        $tmp = imagescale($media_pic, 320, 180);
                        $resize = imagecrop($tmp, ['x' => 0, 'y' => 0, 'width' => 320, 'height' => 180]);
    
                        $media_pic_name = md5(uniqid()).'.'.$originalFileExt;
                        imagejpeg($resize, 'assets/img/trick/post/medias/'.$media_pic_name);
                        imagejpeg($media_pic, 'assets/img/original/'.$media_pic_name);
                    } else if($originalFileExt == 'png') {
                        $media_pic = imagecreatefrompng($media);
                        $tmp = imagescale($media_pic, 320, 180);
                        $resize = imagecrop($tmp, ['x' => 0, 'y' => 0, 'width' => 320, 'height' => 180]);
    
                        $media_pic_name = md5(uniqid()).'.'.$originalFileExt;
                        imagepng($resize, 'assets/img/trick/post/medias/'.$media_pic_name);
                        imagepng($media_pic, 'assets/img/original/'.$media_pic_name);
                    }
    
                    $med = new Media();
                    $med->setName($media_pic_name);
                    $med->setType('image');
                    $trick->addMedia($med);
                }

            }

            if(null !== $form->get('mainpic')->getData()){
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
                    $this->addFlash('error', "Le format d'image n'est pas supporté.");
    
                    return $this->render('trick/edit.html.twig', [
                        'trickform' => $form->createView(),
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

            }

            $em = $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le post a bien été mis à jour.');

            return $this->redirectToRoute('home', ['_fragment' => 'tricks']);
        }

        return $this->render('trick/edit.html.twig', [
            'trickform' => $form->createView(),
            'trick' => $trick,
            ]
        ); 
    }

    /**
     * Page d'édition d'un trick
     *
     * @Route("/trick/{slug}/delete", name="delete_trick")
     */
    public function delete(Request $request, string $slug)
    {
        if($this->getUser()){

            $repository = $this->getDoctrine()->getRepository(Trick::class);
            $trick = $repository->findOneBy(['name' => $slug]);
            $session = new Session();

            if(null !== $request->request->get('delete')) {

                $comment_repository = $this->getDoctrine()->getRepository(Comment::class);
                $media_repository = $this->getDoctrine()->getRepository(Media::class);

                $comments = $comment_repository->findBy(['author' => $trick->getId()]);
                $medias = $media_repository->findBy(['trick' => $trick->getId()]);

                $em = $this->getDoctrine()->getManager();

                if($comments !== null){

                    foreach($comments as $comment){
                        $em->remove($comment);
                        $em->flush();
                    }
        
                }
        
                if($medias !== null){
        
                    foreach($medias as $media){
                        unlink('assets/img/trick/post/medias/'.$media->getName());
                        unlink('assets/img/original/'.$media->getName());
                        $em->remove($media);
                        $em->flush();
                    }
        
                }

                unlink('assets/img/trick/post/'.$trick->getMainpic());
                unlink('assets/img/trick/thumbnails/'.$trick->getMainpic());
                $em->remove($trick);
                $em->flush();

                $this->addFlash('success', 'Le post a bien été supprimé.');

                return $this->redirectToRoute('home', ['_fragment' => 'tricks']);

            }

        }
        
        return $this->render('trick/delete.html.twig', [
                'trick' => $trick
            ]
        ); 
        
    }
}