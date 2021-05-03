<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Trick;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $name = ['Mute', 'Melancholy', 'Tail Grab', 'Nose Grab', '180°', '360°', 'Front Flip', 'Back Flip', 'Nose Slide', 'Backboard Slide'];
        $mainpic = ["06cb67b00dca4afe7c264889f6b19c3f.jpg", "2add0180bf03899b5f385ed0820afbe9.jpg", "c3049a0ee4839e195ca05afe71ed06ad.jpg", "dc416bba7388c9e6be1e298a871a2742.jpg", "df55b29487d09c3a3b6a1bed8e8bc8ac.jpg", "c204974db08facb24f3d2a3f159975f9.jpg", "5dff55ee2cb99380b8464e1d55476650.jpg", "dec8f47cf08b946edbd08663c5804f40.jpg", "88e8da4d56c43729cc24900f66a3a36d.jpg", "0811f77c5454d7fbc9efe7034aa5b4c3.jpg"];
        $content = ["Le mute consiste à attraper la planche entre les deux pieds avec la main avant, la planche doit être maintenue à plat. Pour le réaliser, il faut tout d’abord choisir son point de saut afin d’atterrir en sécurité, ensuite pour bien le réaliser il faut s'assurer de rester bien droit durant le saut pour ensuite fléchir les genoux et attraper la planche avec la main avant. Enfin avant l'atterrissage il faudra lâcher la planche et veiller à atterrir le plus droit possible pour amortir le choc en pliant les genoux.", "De la même manière que le mute, le melancholy consiste à attraper la planche entre les deux pieds avec la main avant, mais cette fois ci, du côté backside de la planche. Ainsi la mise en place du trick est globalement la même que celle du mute, à l’exception de l’endroit ou placer la main.", "Cette figure est l’opposé du Nose grab et se réalise en attrapant l’arrière de la planche à l’aide de la main arrière, pour se faire il faut, après avoir sauté, fléchir le genou arrière afin de lever l’arrière de la planche puis saisir la “tail” a l’aide de la main arrière. Attention à veiller à relâcher la planche et la maintenir droite avant l’atterrissage afin d’éviter de se planter dans la neige la tête la première.", "Un peu plus facile que son similaire Tail grab le Nose Grab lui se réalise en attrapant l’avant de la planche à l’aide de la main avant, pour se faire il faut, après avoir sauté, fléchir le genou avant afin de lever l’avant de la planche puis saisir la “nose” à l’aide de la main avant.", "Le 180° correspond à un demi tour durant un saut, en effet il consiste à finir a l’opposé de la position initiale lors du saut. Plus complexe qu’il n’y parait cette figure demandera de l’expérience avant d’être tenté, elle n’est pas à la portée des débutants et d’autres figures seront conseillées avant de tenter celle ci.", "Plus avancée que le 180°, le 360° et comme son nom l’indique consiste à faire un tour complet lors du saut et de terminer dans la même position qu’au début du saut. A savoir que les rotations telles que le 360° peuvent être frontside ou backside, c’est à dire se faire dans un sens de rotation ou l’autre, également à noter qu’une rotation peut s’accompagner d’un grab par exemple.", "L’une des figures les plus populaires et spectaculaires du snowboard, le front flip, comme son nom l'indique, décrit une rotation verticale à 360°. Aussi complexe que visuelle, cette figure demandera de longues heures d'entraînement et un sacré sang froid pour être réalisée.", "Plus impressionnante mais également plus difficile que le front flip, le back flip lui consiste à réaliser une rotation verticale à 360° vers l’arrière. Si la difficulté de ce trick ne vous effraie pas et que vous cherchez un challenge, sachez que des combinaisons peuvent être réalisées avec cette figure, comme avec des vrilles par exemple, tel que le Mac Twist ou encore le Hakon Flip.", "Figure un peu à part représentant une catégorie bien spécifique du snowboard, le slide, qui consiste à glisser le long d’une barre de différentes manières. Ici le nose slide représente le fait de “slider” sur cette barre à l’aide de l’avant de la planche, comme avec les grabs, l’inverse (le Tail Slide) existe et consiste à réaliser cette même figure à l’aide de l’arrière de la planche.", "Slide un peu plus complexe, le backboard slide consiste à slider dos au mouvement avec la planche centrée par rapport à la barre. A noter ici aussi que des combinaisons de slide sont possibles lors de la glisse, même si le changement de position sur cette barre est bien plus complexe qu’il n’y parait."];
        $category = ['Grab', 'Grab', 'Grab', 'Grab', 'Rotation', 'Rotation', 'Flip', 'Flip', 'Slide', 'Slide'];
        $id_author = ['0', '1', '2', '3', '4'];

        for($i = 0; $i < 10; $i++){
            $trick = new Trick();

            $author = array_rand($id_author);

            $trick_reference = 'trick-'.$i;

            $trick->setName($name[$i]);
            $trick->setMainpic($mainpic[$i]);
            $trick->setContent($content[$i]);
            $trick->setCategory($category[$i]);
            $trick->setAuthor($this->getReference('user-'.$author));

            $this->addReference($trick_reference, $trick);

            $manager->persist($trick);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
