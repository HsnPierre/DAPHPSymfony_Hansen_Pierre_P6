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
     * Page d'un trick
     *
     * @Route("/trick/{slug}", name="show_trick")
     */
    public function index(String $slug)
    {
        $repository = $this->getDoctrine()->getRepository(Trick::class);

        if($repository->findOneBy(['name' => $slug])){
        
            $trick = $repository->findOneBy(['name' => $slug]);

            $date = $trick->getDate('date');
            $date = $date->format('Y-m-d à H:i:s');
            $dateedit = null;

            if($trick->getDateedit() !== null){
                $dateedit = $trick->getDateedit('date');
                $dateedit = $dateedit->format('Y-m-d à H:i:s');
            }

            return $this->render('trick/index.html.twig', [
                    'trick' => $trick,
                    'date' => $date,
                    'dateedit' => $dateedit,
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
     * Page d'ajout d'un trick
     *
     * @Route("/add", name="add_trick")
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
            'trick' => $trick
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