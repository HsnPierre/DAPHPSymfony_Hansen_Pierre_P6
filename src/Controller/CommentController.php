<?php
// src/Controller/CommentController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;

class CommentController extends AbstractController
{

    public function showComments(Trick $trick)
    {
        
    }

    public function deleteComment(int $id)
    {

    }
}