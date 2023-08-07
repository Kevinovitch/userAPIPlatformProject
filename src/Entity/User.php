<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    description: 'This is a simple user.',
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete(),
    ],
    normalizationContext: [
        'groups' => ['user:read'],
    ],
    denormalizationContext: [
        'groups' => ['user:write'],
    ]
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $lastname = null;

    #[ORM\Column()]
    private ?\DateTimeImmutable $creationdate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCreationdate(): ?\DateTimeImmutable
    {
        return $this->creationdate;
    }

    /**
     * A human-readable representation of when the user was created
     * @return string
     */
    #[Groups(['user:read'])]
    public function getCreationdateAgo(): string
    {
        return Carbon::instance($this->creationdate)->diffForHumans();
    }


    #[ORM\PrePersist]
    public function setCreationdate(): void
    {
        $this->creationdate = new \DateTimeImmutable();
    }

    public function getUpdatedate(): ?\DateTimeImmutable
    {
        return $this->updatedate;
    }

    /**
     * A human-readable representation of when the user was updated
     * for the last time
     * @return string
     */
    #[Groups(['user:read'])]
    public function getUpdatedateAgo(): string
    {
        return Carbon::instance($this->updatedate)->diffForHumans();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedate(): void
    {
        $this->updatedate = new \DateTimeImmutable();
    }
}
