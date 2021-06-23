<?php declare(strict_types=1);

namespace App\Menu;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuBuilder
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private EntityManagerInterface $em,
    )
    {
    }

    public function getHeaderMenuItems(): array
    {
        return [
            new MenuItem('Каталог', '#', $this->getMenuItemsForAllCategories()),
            new MenuItem('О нас', $this->urlGenerator->generate('about')),
            new MenuItem('Новости', $this->urlGenerator->generate('news_list')),
        ];
    }

    public function getFooterMenuItems(): array
    {
        return [
            new MenuItem('Каталог', '#', $this->getMenuItemsForAllCategories()),
            new MenuItem('О нас', $this->urlGenerator->generate('about')),
            new MenuItem('Новости', $this->urlGenerator->generate('news_list')),
            new MenuItem('Личный кабинет', $this->urlGenerator->generate('user_profile')),
            new MenuItem('Корзина', $this->urlGenerator->generate('cart_show')),
        ];
    }

    public function getCatalogMenuItems(): array
    {
        return $this->getMenuItemsForAllCategories();
    }

    private function getMenuItemsForAllCategories(): array
    {
        $categoryRepository = $this->em->getRepository(Category::class);

        return array_map(fn(Category $category) => new MenuItem(
            $category->getTitle(),
            $this->urlGenerator->generate('product_category', ['id' => $category->getId()])
        ), $categoryRepository->findAll());
    }
}
