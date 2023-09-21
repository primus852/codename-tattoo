<?php

namespace App\State\User;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\User\UsersDeleteResponseDto;
use App\Entity\User;
use App\Enum\UserRole;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserProcessor implements ProcessorInterface
{

    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return User|mixed
     * @throws UserAlreadyExistsException
     * @throws UserNotFoundException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
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
                    $roles = [];
                    foreach ($data->roles as $preRole) {
                        $roles[] = $this->_createProperRole($preRole);
                    }

                    if (!in_array('ROLE_USER', $roles)) {
                        $roles[] = 'ROLE_USER';
                    }

                    $user->setRoles($roles);
                } else {
                    $user->setRoles(array('ROLE_USER'));
                }

                $repo->save($user, true);
                return $user;

            }

            throw new UserAlreadyExistsException('USER_CREATION_ERROR');

        } elseif ($operation instanceof Patch) {
            if ($context['operation']->getUriTemplate() === '/admin/users') {
                foreach ($data->ids as $id) {
                    $repo = $this->entityManager->getRepository(User::class);
                    $user = $repo->find($id);
                    try {
                        $repo->remove($user, true);
                    } catch (\Exception $e) {
                        throw new UserNotFoundException('USER_DELETION_ERROR');
                    }
                }
                return new UsersDeleteResponseDto($data->ids);
            }
        }

        return $data;
    }

    /**
     * @param string $givenRole
     * @return string
     * @throw InvalidArgumentException
     */
    private function _createProperRole(string $givenRole): string
    {
        $tempRole = strtoupper($givenRole);
        if (!UserRole::isValid($tempRole)) {
            throw new InvalidArgumentException("$tempRole is not a valid user role");
        }
        return 'ROLE_' . strtoupper($tempRole);
    }

}
