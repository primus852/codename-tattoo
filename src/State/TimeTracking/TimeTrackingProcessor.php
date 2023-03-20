<?php

namespace App\State\TimeTracking;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\TimeTracking\TimeTrackingDTO;
use App\Entity\Client;
use App\Entity\Price;
use App\Entity\TimeTracking;
use App\Entity\User;
use App\Enum\TimeTrackingStatus;
use App\Exception\ClientNotFoundException;
use App\Exception\ConfigBlankException;
use App\Exception\ConfigNotFoundException;
use App\Exception\InvalidRoleException;
use App\Exception\NotLoggedInException;
use App\Exception\PriceNotFoundException;
use App\Exception\TimeTrackingNotFoundException;
use App\Exception\UserNotFoundException;
use App\Service\ConfigService;
use App\Service\PublishService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Uid\Uuid;

class TimeTrackingProcessor implements ProcessorInterface
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenStorageInterface  $tokenStorage,
        private readonly PublishService         $publishService
    )
    {
    }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return TimeTrackingDTO
     * @throws ClientNotFoundException
     * @throws ConfigBlankException
     * @throws ConfigNotFoundException
     * @throws InvalidRoleException
     * @throws NotLoggedInException
     * @throws TimeTrackingNotFoundException
     * @throws UserNotFoundException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): TimeTrackingDTO
    {

        $repo = $this->entityManager->getRepository(Price::class);
        $configuredRateHours = $repo->findAll();

        if ($operation instanceof Post) {
            if ($context['operation']->getUriTemplate() === '/time-tracking') {
                $timeTracking = $this->_createAndSaveTimeTracking($data);
                $slots = ConfigService::getRateHoursBetweenDates($timeTracking->getServiceStart(), $timeTracking->getServiceEnd(), $configuredRateHours);
                $tt = new TimeTrackingDTO($timeTracking, $slots);

                $this->publishService->newTimeTrackingAdded($tt);

                return $tt;
            }

            if ($context['operation']->getUriTemplate() === '/process/time-tracking/override') {
                $timeTracking = $this->_overrideOrResetTimeTracking($data);
                $slots = ConfigService::getRateHoursBetweenDates($timeTracking->getServiceStart(), $timeTracking->getServiceEnd(), $configuredRateHours);
                return new TimeTrackingDTO($timeTracking, $slots);
            }

            if ($context['operation']->getUriTemplate() === '/process/time-tracking/update-status') {
                $timeTracking = $this->_updateTimeTrackingStatus($data);
                $slots = ConfigService::getRateHoursBetweenDates($timeTracking->getServiceStart(), $timeTracking->getServiceEnd(), $configuredRateHours);
                return new TimeTrackingDTO($timeTracking, $slots);
            }
        }
        $slots = ConfigService::getRateHoursBetweenDates($data->hourFrom, $data->hourTo, $configuredRateHours);
        return new TimeTrackingDTO($data, $slots);
    }

    /**
     * @throws TimeTrackingNotFoundException
     * @throws InvalidRoleException
     */
    private function _updateTimeTrackingStatus(mixed $data): TimeTracking
    {
        $loggedUser = $this->_getUser();
        $repoTimeTracking = $this->entityManager->getRepository(TimeTracking::class);
        $timeTracking = $repoTimeTracking->find($data->timeTrackingId);

        if ($timeTracking === null) {
            throw new TimeTrackingNotFoundException('TIMETRACKING_NOT_FOUND');
        }

        $status = TimeTrackingStatus::from($data->newStatus);

        /**
         * Only FINANCE can move to finished
         */
        if ($status == TimeTrackingStatus::Finished && !$loggedUser->hasRole('ROLE_FINANCE')) {
            throw new InvalidRoleException('MISSING_FINANCE_PERMISSIONS');
        }

        /**
         * Only FINANCE can move from finished
         */
        if ($timeTracking->getStatus() === TimeTrackingStatus::Finished && !$loggedUser->hasRole('ROLE_FINANCE')) {
            throw new InvalidRoleException('MISSING_FINANCE_PERMISSIONS');
        }

        $timeTracking->setStatus($status);
        $repoTimeTracking->save($timeTracking, true);

        return $timeTracking;

    }

    /**
     * @throws ConfigBlankException
     * @throws TimeTrackingNotFoundException
     * @throws ConfigNotFoundException
     * @throws InvalidRoleException
     */
    private function _overrideOrResetTimeTracking(mixed $data): TimeTracking
    {
        $loggedUser = $this->_getUser();

        if (!$loggedUser->hasRole('ROLE_FINANCE')) {
            throw new InvalidRoleException('MISSING_FINANCE_PERMISSIONS');
        }

        $repoTimeTracking = $this->entityManager->getRepository(TimeTracking::class);
        $timeTracking = $repoTimeTracking->find($data->timeTrackingId);

        if ($timeTracking === null) {
            throw new TimeTrackingNotFoundException('TIMETRACKING_NOT_FOUND');
        }

        if ($data->reset) {
            $timeTracking->setOverrideRateHour(null);
        } else {

            if (!isset($data->overrideToPriceId)) {
                throw new ConfigBlankException('PRICE_BLANK');
            }

            $repoRateHour = $this->entityManager->getRepository(Price::class);
            $configRateHour = $repoRateHour->find($data->overrideToRateHourId);

            if ($configRateHour === null) {
                throw new PriceNotFoundException('PRICE_NOT_FOUND');
            }
            $timeTracking->setOverrideRateHour($configRateHour);
        }

        $repoTimeTracking->save($timeTracking, true);

        return $timeTracking;
    }

    /**
     * If no "userId" is sent, take the logged in one
     * If one is sent, only the ROLE_ADMIN is allowed to use it and add "on behalf"
     * @param mixed $data
     * @return TimeTracking
     * @throws ClientNotFoundException
     * @throws InvalidRoleException
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
                throw new InvalidRoleException('MISSING_ADMIN_PERMISSIONS');
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
