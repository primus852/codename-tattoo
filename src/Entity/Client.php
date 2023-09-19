<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\Client\ClientCreateDto;
use App\Mapping\EntityBase;
use App\Repository\ClientRepository;
use App\State\Client\ClientProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            openapiContext: [
                'tags' => ['Client [Persistence]']
            ],
            paginationItemsPerPage: 10000,
            normalizationContext: [
                'groups' => ['client', 'id']
            ]
        ),
        new Get(
            openapiContext: [
                'tags' => ['Client [Persistence]']
            ],
            normalizationContext: [
                'groups' => ['client', 'timeTracking', 'price', 'id', 'timeonly']
            ],
        ),
        new Post(
            openapiContext: [
                'tags' => ['Client [Persistence]']
            ],
            normalizationContext: [
                'groups' => ['id','client']
            ],
            denormalizationContext: [
                'groups' => 'client'
            ],
            input: ClientCreateDto::class,
            processor: ClientProcessor::class
        )
    ],
    formats: ["json"]

)]
#[ORM\HasLifecycleCallbacks]
class Client extends EntityBase
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['id'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['client'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['client'])]
    private ?string $nameShort = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: TimeTracking::class, orphanRemoval: true)]
    #[Groups(['timeTracking'])]
    private Collection $timeTrackings;

    #[ORM\Column(length: 15, nullable: false)]
    #[Groups(['client'])]
    private ?string $clientNumber = null;

    public function __construct()
    {
        $this->timeTrackings = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNameShort(): ?string
    {
        return $this->nameShort;
    }

    public function setNameShort(string $nameShort): self
    {
        $this->nameShort = $nameShort;

        return $this;
    }

    /**
     * @return Collection<int, TimeTracking>
     */
    public function getTimeTrackings(): Collection
    {
        return $this->timeTrackings;
    }

    public function addTimeTracking(TimeTracking $timeTracking): self
    {
        if (!$this->timeTrackings->contains($timeTracking)) {
            $this->timeTrackings->add($timeTracking);
            $timeTracking->setClient($this);
        }

        return $this;
    }

    public function removeTimeTracking(TimeTracking $timeTracking): self
    {
        if ($this->timeTrackings->removeElement($timeTracking)) {
            // set the owning side to null (unless already changed)
            if ($timeTracking->getClient() === $this) {
                $timeTracking->setClient(null);
            }
        }

        return $this;
    }

    public function getClientNumber(): ?string
    {
        return $this->clientNumber;
    }

    public function setClientNumber(?string $clientNumber): self
    {
        $this->clientNumber = $clientNumber;

        return $this;
    }
}
