<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\Price\PriceCreateDTO;
use App\Dto\Price\PriceDTO;
use App\Dto\Price\PriceInRangeRequestDTO;
use App\Dto\Price\PriceInRangeResponseDTO;
use App\Mapping\EntityBase;
use App\Repository\ConfigPriceRepository;
use App\State\Price\PriceCollectionProvider;
use App\State\Price\PriceCreateProcessor;
use App\State\Price\PriceInRangeProcessor;
use App\State\Price\PriceProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[
    ORM\Entity(repositoryClass: ConfigPriceRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/prices',
            openapiContext: [
                'tags' => ['Prices [Persistence]']
            ],
            provider: PriceCollectionProvider::class
        ),
        new Get(
            uriTemplate: '/price/{id}',
            openapiContext: [
                'tags' => ['Prices [Persistence]']
            ],
            provider: PriceProvider::class,
        ),
        new Post(
            uriTemplate: '/price/create',
            openapiContext: [
                'tags' => ['Prices [Persistence]']
            ],
            normalizationContext: [
                'groups' => 'read'
            ],
            denormalizationContext: [
                'groups' => 'write'
            ],
            input: PriceCreateDTO::class,
            output: PriceDTO::class,
            processor: PriceCreateProcessor::class
        ),
        new Post(
            uriTemplate: '/process/price/in-range',
            openapiContext: [
                'summary' => 'Get all Prices for a given Date Range',
                'description' => 'Retrieves a list of all Prices and their amounts for a given Date Range',
                'tags' => ['Prices [Process]']
            ],
            normalizationContext: [
                'groups' => 'read'
            ],
            denormalizationContext: [
                'groups' => 'write'
            ],
            input: PriceInRangeRequestDTO::class,
            output: PriceInRangeResponseDTO::class,
            processor: PriceInRangeProcessor::class
        ),
    ],
    formats: ["json"]
)]
#[ORM\HasLifecycleCallbacks]
class Price extends EntityBase
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['read'])]
    private ?Uuid $id = null;

    #[ORM\Column]
    private ?float $priceNet = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $category = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeInterface $timeFrom = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeInterface $timeTo = null;

    #[ORM\Column]
    private ?int $weekDay = null;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getTimeFrom(): ?\DateTimeInterface
    {
        return $this->timeFrom;
    }

    public function setTimeFrom(\DateTimeInterface $timeFrom): self
    {
        $this->timeFrom = $timeFrom;

        return $this;
    }

    public function getTimeTo(): ?\DateTimeInterface
    {
        return $this->timeTo;
    }

    public function setTimeTo(\DateTimeInterface $timeTo): self
    {
        $this->timeTo = $timeTo;

        return $this;
    }

    public function getWeekDay(): ?int
    {
        return $this->weekDay;
    }

    public function setWeekDay(int $weekDay): self
    {
        $this->weekDay = $weekDay;

        return $this;
    }
}
