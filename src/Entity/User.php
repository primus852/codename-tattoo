<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Dto\User\UserCreateDto;
use App\Repository\UserRepository;
use App\State\User\UserProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/user',
            openapiContext: [
                'tags' => ['Users [Admin]']
            ],
            normalizationContext: [
                'groups' => 'read'
            ],
            denormalizationContext: [
                'groups' => 'write'
            ],
            input: UserCreateDto::class,
            processor: UserProcessor::class
        ),
        new Get(
            uriTemplate: '/user/{id}',
            openapiContext: [
                'tags' => ['Users [Admin]']
            ],
        ),
    ],
    formats: ["json"],
    routePrefix: '/admin',

)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['write', 'read'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['write', 'read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['write'])]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'serviceUser', targetEntity: TimeTracking::class)]
    #[Groups(['write'])]
    private Collection $timeTrackings;

    #[ORM\Column(length: 15, unique: true, nullable: false)]
    #[Groups(['write', 'read'])]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Groups(['write', 'read'])]
    private ?string $name = null;

    public function __construct()
    {
        $this->timeTrackings = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $timeTracking->setServiceUser($this);
        }

        return $this;
    }

    public function removeTimeTracking(TimeTracking $timeTracking): self
    {
        if ($this->timeTrackings->removeElement($timeTracking)) {
            // set the owning side to null (unless already changed)
            if ($timeTracking->getServiceUser() === $this) {
                $timeTracking->setServiceUser(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
