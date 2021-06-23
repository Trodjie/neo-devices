<?php declare(strict_types=1);

namespace App\Menu;

class MenuItem
{
    public function __construct(
        private string $title,
        private string $path,
        private array $children = []
    )
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }


    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array|\App\Menu\MenuItem[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}
