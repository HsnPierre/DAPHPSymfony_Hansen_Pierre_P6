<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $surname = ['Robin', 'Alice', 'Sacha', 'Filipa', 'Blaise', 'Clothilde', 'Clarisse', 'Nattie', 'Victor', 'Thery', 'Evi', 'Baptiste', 'Romane', 'Hugo', 'Leopold', 'Stacey', 'Myriam', 'Nathan', 'Jean', 'Pierre'];
        $name = ['Martin', 'Bernard', 'Thomas', 'Petit', 'Robert', 'Richard', 'Durand', 'Dubois', 'Moreau', 'Laurent', 'Simon', 'Michel', 'Lefebvre', 'Leroy', 'Roux', 'David', 'Bertrand', 'Morel', 'Fournier', 'Girard'];
        $profilepic = ["https://nsa40.casimages.com/img/2021/03/19/mini_210319072831918051.png", "https://nsa40.casimages.com/img/2021/03/19/mini_210319072831648754.png", "https://nsa40.casimages.com/img/2021/03/19/mini_210319072831377550.png", "https://nsa40.casimages.com/img/2021/03/19/mini_210319072831107206.png", "https://nsa40.casimages.com/img/2021/03/19/mini_210319072830813153.png", "https://nsa40.casimages.com/img/2021/03/19/mini_210319072830540842.png", "https://nsa40.casimages.com/img/2021/03/19/mini_210319072830281957.png", "https://nsa40.casimages.com/img/2021/03/19/mini_21031907283016778.png", "https://nsa40.casimages.com/img/2021/03/19/mini_210319072829751821.png", "https://nsa40.casimages.com/img/2021/03/19/mini_210319072829662565.png"];
        $public_name = ['0', 'name'];
        $public_surname = ['0', 'surname'];
        $public_username = ['0', 'username'];
        $public_mail = ['0', 'mail'];
        $roles = ["ROLE_SUPER_ADMIN", "ROLE_USER"];
        $start = new DateTime();
        $end = new DateTime();

        for($i = 0; $i < 5; $i++){
            $user = new User();

            $prenom = array_rand($surname, 1);
            $nom = array_rand($name, 1);
            $username = $prenom.substr($nom,0,3);
            $mail = strtolower($prenom).'.'.strtolower($nom).'@gmail.com';
            $pp = array_rand($profilepic);
            $tmp_password = md5($mail);
            $password = password_hash($tmp_password, PASSWORD_BCRYPT);
            $public[] = array_rand($public_name);
            $public[] = array_rand($public_surname);
            $public[] = array_rand($public_username);
            $public[] = array_rand($public_mail);
            $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
            $randomDate = new DateTime();
            $randomDate->setTimeStamp($randomTimestamp);
            $role[] = array_rand($roles, 1);

            $user_reference = 'user-'.$i;

            $user->setName($nom);
            $user->setSurname($prenom);
            $user->setUsername($username);
            $user->setMail($mail);
            $user->setPassword($password);
            $user->setProfilepic($pp);
            $user->setRgpd(1);
            $user->setDate($randomDate);
            $user->setRoles($role);
            $user->setPublic($public);

            $this->addReference($user_reference, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
