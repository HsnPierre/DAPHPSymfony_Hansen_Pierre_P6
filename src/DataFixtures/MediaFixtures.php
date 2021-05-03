<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\TrickFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Media;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $images = ["1b3e78971778a42af3c8f5fcfe8a478d.jpg","1e93fe52701ff8fc6ac6e11a7c4b3f87.jpg", "8b6724ed676f3021765c846a87a0c963.jpg", "11b530e75ed1e0a13e59d1794150cc34.jpg", "51c1ca100e1b4ddf34bd16cb47a0f659.jpg", "96aedc60b5b47c8f707e4657f64fbf29.jpg", "244be67b2d82c6c4444a2c1fe8880d3d.jpg", "419e3074db6cab2276bf09a83f3e6f35.jpg", "885a2d0d7b84347a6a449d60cfec22ce.jpg", "63985c7ba25b7e679c208777b6801e3a.jpeg", "8905994a9405bcbad08768352388c675.jpg", "71264833b5f13b809ffeddb15ab5dee7.jpg", "aa94146a81859b349c18df7d1655b48c.jpg", "e35b5f775ab3ea87a482aeb7aa460741.jpg", "ebbdcc1ccae6de32c8273b0186e98242.jpg"];
        $videos = ["https://youtu.be/SQyTWk7OxSI", "https://youtu.be/gbHU6J6PRRw", "https://youtu.be/xhvqu2XBvI0", "https://youtu.be/SFYYzy0UF-8", "https://youtu.be/5mtNg2mf-Hg", "https://youtu.be/2iYibvfBiXE", "https://youtu.be/1AibZIwxnuU", "https://youtu.be/R2Cp1RumorU", "https://youtu.be/CstnNngasZQ", "https://youtu.be/arzLq-47QFA"];
        
        for($i = 0; $i < 10; $i+=2){
            
            for($j = 1; $j < 3; $j++){

                if($j % 2){
                    $media = new Media();

                    $nom = $images[array_rand($images)];

                    $media->setName($nom);
                    $media->setType('image');
                    $media->setTrick($this->getReference('trick-'.$i));
        
                    $manager->persist($media);
                } else {

                    $media = new Media();

                    $url = $videos[array_rand($videos)];
                    $nom = str_replace("https://youtu.be/", '', $url);

                    $media->setName($nom);
                    $media->setUrl($url);
                    $media->setType('video');
                    $media->setTrick($this->getReference('trick-'.$i));
        
                    $manager->persist($media);

                }

            }

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
