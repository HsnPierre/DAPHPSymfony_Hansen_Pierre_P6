<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Trick;
use App\Entity\User;

class MainController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * @Route("/", name="home")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Trick::class);
        $tricks = $repository->findBy(array(), array('category'=>'ASC'));

        $pag_tricks = $paginator->paginate($tricks, $request->query->getInt('page', 1), 6);

        $session = new Session();
        $session->start();

        $success = null;
        if(null !== $session->get('success')){
            $success = $session->remove('success');
        }

        return $this->render('main/index.html.twig', [
            'tricks' => $pag_tricks,
            'success' => $success,
            ]
        );
    }
}