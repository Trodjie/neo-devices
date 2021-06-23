<?php declare(strict_types=1);

namespace App\Entity;


use App\DTO\Profile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @method string getUserIdentifier()
 */
#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $password;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $lastname;

    #[ORM\Column]
    private string $address;

    #[ORM\Column]
    private string $phone;

    public function __construct(string $email, string $name, string $lastname, string $address, string $phone)
    {
        $this->email    = $email;
        $this->name     = $name;
        $this->lastname = $lastname;
        $this->address  = $address;
        $this->phone    = $phone;
    }

    public function updateFromDto(Profile $profile): void
    {
        $this->email    = $profile->email;
        $this->name     = $profile->name;
        $this->lastname = $profile->lastname;
        $this->address  = $profile->address;
        $this->phone    = $profile->phone;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {

    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }
}
