<?php 

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController {
    #[Route('/admin/category/new', name: 'admin_category_new_get', methods: ['GET', 'HEAD'])]
    public function new(): Response
    {
        return $this->render('admin.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'admin/category/new.html.twig'
        ]);
    }
}