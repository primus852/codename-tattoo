<?php

namespace App\State\TimeTracking;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\TimeTracking\TimeTrackingDto;
use App\Entity\Client;
use App\Entity\TimeTracking;
use App\Entity\User;
use App\Enum\TimeTrackingStatus;
use App\Exception\ClientNotFoundException;
use App\Exception\NoAdminException;
use App\Exception\NotLoggedInException;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Uid\Uuid;

class TimeTrackingProcessor implements ProcessorInterface
{

    private EntityManagerInterface $entityManager;
    private TokenStorageInterface $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @throws NotLoggedInException
     * @throws NoAdminException
     * @throws ClientNotFoundException
     * @throws UserNotFoundException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): TimeTrackingDto
    {
        if ($operation instanceof Post) {
            $timeTracking = $this->_createAndSaveTimeTracking($data);
            return new TimeTrackingDto($timeTracking);
        }

        return new TimeTrackingDto($data);
    }

    /**
     * If no "userId" is sent, take the logged in one
     * If one is sent, only the ROLE_ADMIN is allowed to use it and add "on behalf"
     * @param mixed $data
     * @return TimeTracking
     * @throws ClientNotFoundException
     * @throws NoAdminException
     * @throws NotLoggedInException
     * @throws UserNotFoundException
     */
    private function _createAndSaveTimeTracking(mixed $data): TimeTracking
    {
        $repo = $this->entityManager->getRepository(TimeTracking::class);
        $loggedUser = $this->_getUser();

        if (isset($data->userId)) {
            $user = $loggedUser;
            if (!$loggedUser->hasRole('ROLE_ADMIN')) {
                throw new NoAdminException('MISSING_ADMIN_PERMISSIONS');
            }
            if ($user === null) {
                throw new UserNotFoundException('USER_NOT_FOUND');
            }
        } else {
            $user = $this->_getUser();
            if ($user === null) {
                throw new NotLoggedInException('NOT_LOGGED_IN');
            }
        }

        $client = $this->_getClient($data->clientId);

        if ($client === null) {
            throw new ClientNotFoundException('CLIENT_NOT_FOUND');
        }

        $entry = new TimeTracking();
        $entry->setServiceUser($user);
        $entry->setServiceDescription($data->description);
        $entry->setStatus(TimeTrackingStatus::Open);
        $entry->setServiceStart($data->dateStart);
        $entry->setServiceEnd($data->dateEnd);
        $entry->setClient($client);

        $repo->save($entry, true);

        return $entry;
    }

    private function _getClient(Uuid $clientId): ?Client
    {
        return $this->entityManager->getRepository(Client::class)->find($clientId);
    }

    private function _getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}
