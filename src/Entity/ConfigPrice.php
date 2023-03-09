<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\Config\ConfigPriceCreateDTO;
use App\Dto\Config\ConfigPriceDTO;
use App\Mapping\EntityBase;
use App\Repository\ConfigPriceRepository;
use App\State\Config\ConfigPriceCollectionProvider;
use App\State\Config\ConfigPriceCreateProcessor;
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
            uriTemplate: '/config/prices',
            openapiContext: [
                'tags' => ['Prices [Persistence]']
            ],
            provider: ConfigPriceCollectionProvider::class
        ),
        new Post(
            uriTemplate: '/config/create-price',
            openapiContext: [
                'tags' => ['Prices [Persistence]']
            ],
            normalizationContext: [
                'groups' => 'read'
            ],
            denormalizationContext: [
                'groups' => 'write'
            ],
            input: ConfigPriceCreateDTO::class,
            output: ConfigPriceDTO::class,
            processor: ConfigPriceCreateProcessor::class
        )
    ],
    formats: ["json"]
)]
#[ORM\HasLifecycleCallbacks]
class ConfigPrice extends EntityBase
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
