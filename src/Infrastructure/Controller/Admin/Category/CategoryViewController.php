<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin\Category;

use App\Application\CategoryView\CategoryViewService;
use App\Domain\Category\NoSuchCategoryException;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class CategoryViewController extends AbstractController
{

    public function __construct(
        private readonly CategoryViewService $categoryViewService
    ) {}
    #[Route('/admin/category', name: 'admin_category_list', methods: ['GET', 'HEAD'])]
    public function list(): Response
    {
        $categories = $this->categoryViewService->listAll();

        return $this->render('admin.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'admin/category/list.html.twig',
            'categories' => $categories
        ]);
    }

    #[Route('/admin/category/{id}', name: 'category_view', methods: ['GET', 'HEAD'])]
    public function view($id): Response
    {
        try {
            $categoryDto = $this->categoryViewService->find($id);
            return $this->render('admin.html.twig', [
                'controller_name' => 'ArticleController',
                'controller_template' => 'admin/category/view.html.twig',
                'category' => $categoryDto
            ]);
        } catch (NoSuchCategoryException) {
            throw $this->createNotFoundException(
                sprintf('category with id %s not found', $id)
            );
        } catch (InvalidArgumentException $e) {
            throw $this->createNotFoundException(
                sprintf('category with id %s not found', $id)
            );
        }
    }

}
