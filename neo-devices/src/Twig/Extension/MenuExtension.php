<?php declare(strict_types=1);

namespace App\Twig\Extension;

use App\Menu\MenuBuilder;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function __construct(
        private MenuBuilder $menuBuilder
    )
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('header_menu', [$this, 'renderHeaderMenu'], [
                'is_safe' => ['html'],
                'needs_environment' => true
            ]),
            new TwigFunction('footer_menu', [$this, 'renderFooterMenu'], [
                'is_safe' => ['html'],
                'needs_environment' => true
            ]),
            new TwigFunction('catalog_menu', [$this, 'renderCatalogMenu'], [
                'is_safe' => ['html'],
                'needs_environment' => true
            ]),
        ];
    }

    public function renderHeaderMenu(Environment $twig): string
    {
        return $twig->render('menu/header.html.twig', [
            'items' => $this->menuBuilder->getHeaderMenuItems(),
        ]);
    }

    public function renderFooterMenu(Environment $twig): string
    {
        return $twig->render('menu/footer.html.twig', [
            'items' => $this->menuBuilder->getFooterMenuItems(),
        ]);
    }

    public function renderCatalogMenu(Environment $twig): string
    {
        return $twig->render('menu/catalog.html.twig', [
            'items' => $this->menuBuilder->getCatalogMenuItems(),
        ]);
    }
}
