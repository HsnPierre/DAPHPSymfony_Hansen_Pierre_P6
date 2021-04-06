<?php
// src/Controller/TrickController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Trick;
use App\Form\TrickType;

class TrickController extends AbstractController
{
    
    /**
     * Page d'ajout d'un trick
     *
     * @Route("/trick/add", name="add_trick")
     */
    public function add(Request $request)
    {
        $trick = new Trick();
        $user = $this->getUser();
        
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $trick->setAuthor($user);
        }

        if($form->isSubmitted() && $form->isValid()) {

            $medias = $form->get('media')->getData();
            $med = [];

            $post_pic = imagecreatefrompng($form->get('mainpic')->getData());
            $fs_post_pic = imagescale($post_pic, 1120, 560);
            $thumbnail_post_pic = imagescale($post_pic, 650, 350);

            if ($fs_post_pic !== false && $thumbnail_post_pic !== false) {
                $post_pic_name = md5(uniqid()).'.png';
                imagepng($fs_post_pic, 'assets/img/trick/post/'.$post_pic_name);
                imagepng($thumbnail_post_pic, 'assets/img/trick/thumbnails/'.$post_pic_name);

                $trick->setMainpic($post_pic_name);
            }
 
            foreach($medias as $media){
                
                $fichier = md5(uniqid()).strstr($media, '.');

                $media->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $med[] = $fichier;
            }

            $trick->setMedia($med);

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
    public function index(String $slug)
    {
        $repository = $this->getDoctrine()->getRepository(Trick::class);
        $trick = $repository->findOneBy(['name' => $slug]);

        if($trick){
        
            $date = $trick->getDate('date');
            $date = $date->format('Y-m-d à H:i:s');
            $dateedit = null;
            $mainpic = 'assets/img/trick/post/'.$trick->getMainpic();

            if($trick->getDateedit() !== null){
                $dateedit = $trick->getDateedit('date');
                $dateedit = $dateedit->format('Y-m-d à H:i:s');
            }

            return $this->render('trick/index.html.twig', [
                    'trick' => $trick,
                    'date' => $date,
                    'dateedit' => $dateedit,
                    'mainpic' => $mainpic,
                    'error' => null
                ]
            );
        
        } else {
            $error ="Ce trick n'existe pas.";

            return $this->render('trick/index.html.twig', [
                    'error' => $error
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

    public function crop()
    {
        
    }
}