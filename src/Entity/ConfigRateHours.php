<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\Config\ConfigRateHoursCreateDto;
use App\Dto\Config\ConfigRateHoursDto;
use App\Dto\Config\ConfigRateHoursRequestSlotsDto;
use App\Dto\Config\ConfigRateHoursSlotsDto;
use App\Mapping\EntityBase;
use App\Repository\ConfigRateHoursRepository;
use App\State\Config\ConfigRateHoursProcessor;
use App\State\Config\ConfigRateHoursProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConfigRateHoursRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/config/rate-hours',
            openapiContext: [
                'tags' => ['Rate Hours [Persistence]']
            ],
        ),
        new Get(
            uriTemplate: '/config/rate-hour/{id}',
            openapiContext: [
                'tags' => ['Rate Hours [Persistence]']
            ],
        ),
        new Post(
            uriTemplate: '/process/rate-hour/combined',
            openapiContext: [
                'summary' => 'Get all Rate Hours for a given Date Range',
                'description' => 'Retrieves a list of all Rate Hours and their amounts for a given Date Range',
                'tags' => ['Rate Hours [Process]']
            ],
            normalizationContext: [
                'groups' => 'read'
            ],
            denormalizationContext: [
                'groups' => 'write'
            ],
            input: ConfigRateHoursRequestSlotsDto::class,
            output: ConfigRateHoursSlotsDto::class
        ),
        new Post(
            uriTemplate: '/config/rate-hour',
            openapiContext: [
                'tags' => ['Rate Hours [Persistence]']
            ],
            normalizationContext: [
                'groups' => 'read'
            ],
            denormalizationContext: [
                'groups' => 'write'
            ],
            input: ConfigRateHoursCreateDto::class,
            output: ConfigRateHoursDto::class
        )
    ],
    formats: ["json"],
    provider: ConfigRateHoursProvider::class,
    processor: ConfigRateHoursProcessor::class
)]
#[ORM\HasLifecycleCallbacks]
class ConfigRateHours extends EntityBase
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['read'])]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    #[Groups(['write', 'read'])]
    private ?\DateTimeImmutable $hourFrom = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    #[Groups(['write', 'read'])]
    private ?\DateTimeImmutable $hourTo = null;

    #[ORM\Column]
    #[Groups(['write', 'read'])]
    private ?float $priceNet = null;

    #[ORM\Column(length: 255)]
    #[Groups(['write', 'read'])]
    private ?string $category = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getHourFrom(): ?\DateTimeImmutable
    {
        return $this->hourFrom;
    }

    public function setHourFrom(\DateTimeImmutable $hourFrom): self
    {
        $this->hourFrom = $hourFrom;

        return $this;
    }

    public function getHourTo(): ?\DateTimeImmutable
    {
        return $this->hourTo;
    }

    public function setHourTo(\DateTimeImmutable $hourTo): self
    {
        $this->hourTo = $hourTo;

        return $this;
    }

    public function getPriceNet(): ?float
    {
        return $this->priceNet;
    }

    public function setPriceNet(float $priceNet): self
    {
        $this->priceNet = $priceNet;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
}
