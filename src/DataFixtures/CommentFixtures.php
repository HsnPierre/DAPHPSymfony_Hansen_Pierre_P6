<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\TrickFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Comment;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $content = ["Superbe figure !", "J'ai vraiment apprécié cet article !", "Wow impressionnant !", "Merci pour ce tuto !", "J'essaierai à mes prochaines vancances.", "J'aime !", "Difficile celle ci !", "Quelqu'un l'a t-il déjà essayée ?", "Quel talent !", "Très bon article !", "Vivement la réouverture des pistes.", "C'est pas trop dangereux ?", "Combien de temps d'entrainement estimez vous nécessaire à la réalisation de cette figure ?"];
        $id_author = ['0', '1', '2', '3', '4'];
        $id_trick = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        for($i = 0; $i < 15; $i++){
            $comment = new Comment();

            $author = array_rand($id_author);
            $trick = array_rand($id_trick);
            $contenu = $content[array_rand($content)];

            $comment->setContent($contenu);
            $comment->setRgpd(1);
            $comment->setAuthor($this->getReference('user-'.$author));
            $comment->setTrick($this->getReference('trick-'.$trick));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TrickFixtures::class,
        );
    }
}
