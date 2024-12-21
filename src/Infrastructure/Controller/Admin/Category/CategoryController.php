<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin\Category;

use App\Application\Category\AddFromForm;
use App\Application\Category\CategoryService;
use App\Domain\Category\CouldNotDeleteException;
use App\Domain\Category\CouldNotSaveException;
use App\Domain\Category\NoSuchCategoryException;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{

    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly UrlHelper $urlHelper,
    ) {}

    #[Route('/admin/category/new', name: 'admin_category_new_get', methods: ['GET', 'HEAD'])]
    public function new(): Response
    {
        return $this->render('admin.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'admin/category/new.html.twig'
        ]);
    }

    #[Route('/admin/category/save', name: 'category_save', methods: ['POST'])]
    public function save(Request $request): Response
    {
        $params = $request->request->all();
        try {
            $id = $this->categoryService->save(AddFromForm::fromHash($params));
        } catch (CouldNotSaveException) {
            return new Response('category was not save, please try later');
        } catch (InvalidArgumentException $e) {
            return new Response(
                sprintf('Error while creating new category: %s', $e->getMessage())
            );
        } catch (NoSuchCategoryException) {
            throw $this->createNotFoundException(
                sprintf('category with id %s not found', $id)
            );
        }

        $url = $this->urlHelper->getAbsoluteUrl(sprintf('/admin/category/%s', $id()));

        return new RedirectResponse($url);
    }

    #[Route('/admin/category/delete/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete($id): Response
    {
        try {
            $this->categoryService->delete($id);

            $url = $this->urlHelper->getAbsoluteUrl('/admin/category');
            return new RedirectResponse($url);
        } catch (NoSuchCategoryException) {
            return new Response(
                sprintf('category with id %s not found', $id)
            );
        } catch (InvalidArgumentException $e) {
            return new Response(
                sprintf('Cannot delete category with id %s: %s', $id, $e->getMessage())
            );
        } catch (CouldNotDeleteException) {
            return new Response(
                sprintf('Cannot delete category with id %s, please try later', $id)
            );
        }
    }
}
