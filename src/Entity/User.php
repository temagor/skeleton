<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Actions\User\StoreAction as UserStoreAction;

#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('login')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'login required')]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $login = null;

    #[ORM\Column]
    private ?bool $protected = false;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Credential::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $credentials;

    public function __construct()
    {
        $this->credentials = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function exceptions(): void
    {
        $isCorrectMethodSaving = false;
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($backtrace as $trace) {
            if (stripos($trace['file'], 'StoreAction')) {
                $isCorrectMethodSaving = true;
                break;
            }
        }
        if (!$isCorrectMethodSaving) {
            throw new Exception("use " . UserStoreAction::class . ' to create user!');
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function isProtected(): ?bool
    {
        return $this->protected;
    }

    public function setProtected(bool $protected): self
    {
        $this->protected = $protected;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
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
        $loginCredential = $this->credentials->filter(function (Credential $credential) {
            return $credential->getType() == 'login';
        })->first();
        if (!$loginCredential instanceof Credential) {
            throw new Exception('No password credentials');
        }
        return $loginCredential->getValue();
    }

    public function setPassword(string $password): self
    {
        $loginCredential = $this->credentials->filter(function (Credential $credential) {
            return $credential->getType() == 'login';
        })->first();
        if (!$loginCredential instanceof Credential) {
            throw new Exception('No password credentials');
        }
        $loginCredential->setValue($password);
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Credential>
     */
    public function getCredentials(): Collection
    {
        return $this->credentials;
    }

    public function addCredential(Credential $credential): self
    {
        if (!$this->credentials->contains($credential)) {
            $this->credentials->add($credential);
            $credential->setUser($this);
        }

        return $this;
    }

    public function removeCredential(Credential $credential): self
    {
        if ($this->credentials->removeElement($credential)) {
            // set the owning side to null (unless already changed)
            if ($credential->getUser() === $this) {
                $credential->setUser(null);
            }
        }

        return $this;
    }
}
