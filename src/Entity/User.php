<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="users")
     */
    private $Fav;

    /**
     * @ORM\ManyToMany(targetEntity=Serie::class, inversedBy="users")
     */
    private $favShow;

    public function __construct()
    {
        $this->Fav = new ArrayCollection();
        $this->favShow = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
     * @see UserInterface
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getFav(): Collection
    {
        return $this->Fav;
    }

    public function addFav(Movie $fav): self
    {
        if (!$this->Fav->contains($fav)) {
            $this->Fav[] = $fav;
        }

        return $this;
    }

    public function removeFav(Movie $fav): self
    {
        $this->Fav->removeElement($fav);

        return $this;
    }

    /**
     * @return Collection|Serie[]
     */
    public function getFavShow(): Collection
    {
        return $this->favShow;
    }

    public function addFavShow(Serie $favShow): self
    {
        if (!$this->favShow->contains($favShow)) {
            $this->favShow[] = $favShow;
        }

        return $this;
    }

    public function removeFavShow(Serie $favShow): self
    {
        $this->favShow->removeElement($favShow);

        return $this;
    }
}
