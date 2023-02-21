<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use App\Repository\ConfigWeekDaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConfigWeekDaysRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ConfigWeekDays extends EntityBase
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['read'])]
    private ?Uuid $id = null;

    #[ORM\Column]
    private ?int $dayOfWeek = null;

    #[ORM\ManyToMany(targetEntity: ConfigRateHours::class, inversedBy: 'configWeekDays')]
    private Collection $configRateHour;

    public function __construct()
    {
        $this->configRateHour = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    /**
     * @return Collection<int, ConfigRateHours>
     */
    public function getConfigRateHour(): Collection
    {
        return $this->configRateHour;
    }

    public function addConfigRateHour(ConfigRateHours $configRateHour): self
    {
        if (!$this->configRateHour->contains($configRateHour)) {
            $this->configRateHour->add($configRateHour);
        }

        return $this;
    }

    public function removeConfigRateHour(ConfigRateHours $configRateHour): self
    {
        $this->configRateHour->removeElement($configRateHour);

        return $this;
    }
}
