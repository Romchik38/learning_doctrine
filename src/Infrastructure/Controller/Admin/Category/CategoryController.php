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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** Notification */

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

#[IsGranted('ROLE_ADMIN')]
final class CategoryController extends AbstractController
{

    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly UrlHelper $urlHelper,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {}

    #[Route('/admin/category/new', name: 'admin_category_new_get', methods: ['GET', 'HEAD'])]
    public function new(Request $request): Response
    {

        $errorMessage = $request->query->get('error-message');

        return $this->render('admin.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'admin/category/new.html.twig',
            'error_message' => $errorMessage
        ]);
    }

    #[Route('/admin/category/save', name: 'category_save', methods: ['POST'])]
    public function save(
        Request $request,
        NotifierInterface $notifier
    ): Response {
        $params = $request->request->all();
        // 1. csrf
        $token = $params['token'] ?? null;
        if (!$this->isCsrfTokenValid('new-category', $token)) {
            // return back to new category page
            $newCategoryurl = $this->urlGenerator->generate('admin_category_new_get', [
                'error-message' => 'Form is invalid'
            ]);
            return new RedirectResponse($newCategoryurl);
        }
        // 2. save
        try {
            $id = $this->categoryService->save(AddFromForm::fromHash($params));
        } catch (CouldNotSaveException) {
            return new Response('category was not save, please try later');
        } catch (InvalidArgumentException $e) {
            return new Response(
                sprintf('Error while saving category: %s', $e->getMessage())
            );
        } catch (NoSuchCategoryException) {
            throw $this->createNotFoundException(
                sprintf('category with id %s not found', $id)
            );
        }

        $notifier->send(new Notification('Category was saved', ['browser']));

        $url = $this->urlGenerator->generate('category_view', [
            'id' => $id()
        ]);

        return new RedirectResponse($url);
    }

    #[Route('/admin/category/delete/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(
        $id,
        NotifierInterface $notifier
    ): Response {
        try {
            $this->categoryService->delete($id);

            $notification =
                (new Notification('Category was deleted', ['email']))
                ->content(sprintf('Category with id %s was deleted', $id));
            $recipient = new Recipient('admin@localhost.com');
            $notifier->send($notification, $recipient);

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
