<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements FixtureGroupInterface
{

    public static function getGroups(): array
    {
        return ['users'];
    }

    public function load(ObjectManager $manager): void
    {
        /**
         * Create the Admin
         */
        $this->_generateUser($manager);

        /**
         * Create 4 Regular Users
         */
        $this->_generateUser($manager, UserRole::USER, 4);
    }

    /**
     * @param ObjectManager $manager
     * @param UserRole $role
     * @param int $numberOfUsers
     * @return void
     */
    private function _generateUser(ObjectManager $manager, UserRole $role = UserRole::ADMIN, int $numberOfUsers = 1): void
    {
        if ($role === UserRole::ADMIN) {
            $user = new User();
            $user->setEmail('admin@hyvee.de');
            $user->setRoles(array('ROLE_'.UserRole::ADMIN->toString()));
            $user->setCode('AD');
            $user->setName('Admin');
            $user->setPassword('$2a$12$eaGjY48bAXkYCqSseLZxHePa0Jj7SGK7OR8k66B9/DulY8QbRZXLu');
            $manager->persist($user);
        } else {
            for ($i = 0; $i < $numberOfUsers; $i++) {
                $user = new User();
                $user->setEmail('user' . $i . '@hyvee.de');
                $user->setRoles(array('ROLE_'.UserRole::USER->toString()));
                $user->setCode('US' . $i);
                $user->setName('User' . $i);
                $user->setPassword('$2a$12$eaGjY48bAXkYCqSseLZxHePa0Jj7SGK7OR8k66B9/DulY8QbRZXLu');
                $manager->persist($user);
            }
        }

        $manager->flush();
    }
}
