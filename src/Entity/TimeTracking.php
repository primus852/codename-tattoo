<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\TimeTracking\TimeTrackingCreateDto;
use App\Dto\TimeTracking\TimeTrackingDto;
use App\Enum\TimeTrackingStatus;
use App\Mapping\EntityBase;
use App\Repository\TimeTrackingRepository;
use App\State\TimeTracking\TimeTrackingProcessor;
use App\State\TimeTracking\TimeTrackingProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TimeTrackingRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/time-tracking/{id}',
            openapiContext: [
                'tags' => ['Time Tracking [Persistence]']
            ],
            output: TimeTrackingDto::class,
        ),
        new GetCollection(
            uriTemplate: '/time-trackings',
            openapiContext: [
                'tags' => ['Time Tracking [Persistence]']
            ],
        ),
        new Post(
            uriTemplate: '/time-tracking',
            openapiContext: [
                'summary' => 'Create a new TimeTracking (self or on behalf)',
                'description' => 'Creates a new TimeTracking. Provide userId to create "on behalf" (ROLE_ADMIN only)',
                'tags' => ['Time Tracking [Persistence]']
            ],
            input: TimeTrackingCreateDto::class,
            output: TimeTrackingDto::class,
        )
    ],
    formats: ["json"],
    provider: TimeTrackingProvider::class,
    processor: TimeTrackingProcessor::class
)]
#[ORM\HasLifecycleCallbacks]
class TimeTracking extends EntityBase
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serviceDescription = null;

    #[ORM\Column(length: 255, enumType: TimeTrackingStatus::class)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'enum' => ['OPEN', 'NONE', 'NONE_SHOW', 'FINISHED'],
            'example' => 'OPEN'
        ]
    )]
    private ?TimeTrackingStatus $status = null;

    #[ORM\ManyToOne(inversedBy: 'timeTrackings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $serviceUser = null;

    #[ORM\Column()]
    private ?\DateTimeImmutable $serviceStart = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $serviceEnd = null;

    #[ORM\ManyToOne(inversedBy: 'timeTrackings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getServiceDescription(): ?string
    {
        return $this->serviceDescription;
    }

    public function setServiceDescription(?string $serviceDescription): self
    {
        $this->serviceDescription = $serviceDescription;

        return $this;
    }

    public function getStatus(): ?TimeTrackingStatus
    {
        return $this->status;
    }

    public function setStatus(TimeTrackingStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getServiceUser(): ?User
    {
        return $this->serviceUser;
    }

    public function setServiceUser(?User $serviceUser): self
    {
        $this->serviceUser = $serviceUser;

        return $this;
    }

    public function getServiceStart(): ?\DateTimeImmutable
    {
        return $this->serviceStart;
    }

    public function setServiceStart(\DateTimeImmutable $serviceStart): self
    {
        $this->serviceStart = $serviceStart;

        return $this;
    }

    public function getServiceEnd(): ?\DateTimeImmutable
    {
        return $this->serviceEnd;
    }

    public function setServiceEnd(?\DateTimeImmutable $serviceEnd): self
    {
        $this->serviceEnd = $serviceEnd;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
