<?php

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserProcessor implements ProcessorInterface
{

    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof Post) {
            $repo = $this->entityManager->getRepository(User::class);
            $exists = $repo->loadUserByIdentifierOrCode($data->email, $data->code);

            if ($exists === null) {
                $user = new User();
                $user->setEmail($data->email);
                $user->setCode($data->code);
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $data->password));

                if (isset($data->name)) {
                    $user->setName($data->name);
                }

                if (isset($data->roles)) {
                    $user->setRoles($data->roles);
                } else {
                    $user->setRoles(array('ROLE_USER'));
                }

                $repo->save($user, true);
                return $user;

            }

            throw new UserAlreadyExistsException('USER_CREATION_ERROR');

        }

        return $data;
    }
}
