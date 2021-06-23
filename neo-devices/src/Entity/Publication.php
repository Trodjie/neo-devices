<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\PublicationRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    private int $id;

    #[ORM\Column]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $text;

    #[ORM\Column(nullable: true)]
    private string $annotation;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $publishedAt;

    #[ORM\Column(nullable: true)]
    private ?string $imagePath;

    public function __construct(string $title, string $text)
    {
        $this->title       = $title;
        $this->text        = $text;
        $this->publishedAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Publication
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): Publication
    {
        $this->text = $text;

        return $this;
    }

    public function getAnnotation(): string
    {
        return $this->annotation;
    }

    public function setAnnotation(string $annotation): Publication
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function getPublishedAt(): DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTimeInterface $publishedAt): Publication
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): Publication
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
