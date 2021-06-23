<?php declare(strict_types=1);

namespace App\DTO;

use App\Constraints\UniqueValueInEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueValueInEntity(class: \App\Entity\User::class, fields: ['email' => 'email'], identifier: 'userId')]
class Profile
{
    public int $userId;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $address;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $lastname;

    #[Assert\NotBlank]
    public string $phone;

    public static function createFromEntity(\App\Entity\User $user): self
    {
        $profile = new self();

        $profile->userId   = $user->getId();
        $profile->email    = $user->getEmail();
        $profile->name     = $user->getName();
        $profile->lastname = $user->getLastname();
        $profile->address  = $user->getAddress();
        $profile->phone    = $user->getPhone();

        return $profile;
    }
}
