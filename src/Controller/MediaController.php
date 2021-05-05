<?php
// src/Controller/MediaController.php
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

class MediaController extends AbstractController
{

    /**
     * Suppression d'un media
     * 
     * @Route("/trick/{slug}/medias", name="delete_media")
     */
    public function delete(Request $request, string $slug)
    {
        $trick_repository = $this->getDoctrine()->getRepository(Trick::class);
        $trick = $trick_repository->findOneBy(['name' => $slug]);
        $medias = $trick->getMedias();

        foreach($medias as $media){
            if($request->request->get('deletemedia') == $media->getName()){
                if($media->getType() == 'image'){

                    unlink('assets/img/trick/post/medias/'.$media->getName());
                    unlink('assets/img/original/'.$media->getName());
                    $em = $this->getDoctrine()->getManager();
        
                    $em->remove($media);
                    $em->flush();
        
                    return $this->redirectToRoute('delete_media', ['slug' => $slug]);
        
                } elseif($media->getType() == 'video'){
                    
                    $em = $this->getDoctrine()->getManager();
        
                    $em->remove($media);
                    $em->flush();
        
                    return $this->redirectToRoute('delete_media', ['slug' => $slug]);
                    
                }
            }
        }

        return $this->render('trick/medias.html.twig', [
            'trick' => $trick,
            ]
        ); 
    }
}