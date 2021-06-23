<?php declare(strict_types=1);

namespace App\DTO;


use App\Constraints\UniqueValueInEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueValueInEntity(class: \App\Entity\User::class, fields: ['email' => 'email'])]
class User
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $rawPassword;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $lastname;

    #[Assert\NotBlank]
    public string $address;

    #[Assert\NotBlank]
    public string $phone;
}
