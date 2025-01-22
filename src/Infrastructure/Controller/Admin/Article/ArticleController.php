<?php

namespace App\Infrastructure\Controller\Admin\Article;

use App\Application\Article\AddFromForm;
use App\Application\Article\ArticleService;
use App\Domain\Article\CannotActivateArticle;
use App\Domain\Article\CouldNotDeleteException;
use App\Domain\Article\CouldNotSaveException;
use App\Domain\Article\NoSuchArticleException;
use App\Domain\Category\NoSuchCategoryException;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class ArticleController extends AbstractController
{

    public function __construct(
        protected readonly ArticleService $articleService,
        protected UrlHelper $urlHelper,
    ) {}

    #[Route('/admin/article/new', name: 'admin_article_new_get', methods: ['GET', 'HEAD'])]
    public function new(): Response
    {
        return $this->render('admin.html.twig', [
            'controller_name' => 'ArticleController',
            'controller_template' => 'admin/article/new.html.twig'
        ]);
    }

    #[Route('/admin/article/save', name: 'admin_article_save', methods: ['POST'])]
    public function save(Request $request): Response
    {
        $params = $request->request->all();
        $formData = AddFromForm::fromHash($params);
        try {
            $id = $this->articleService->save($formData);
        } catch (CouldNotSaveException) {
            return new Response('article was not save, please try later');
        } catch (InvalidArgumentException $e) {
            return new Response(
                sprintf('Error while creating new article: %s', $e->getMessage())
            );
        } catch(NoSuchArticleException) {
            return new Response(
                sprintf('Article with id %s not found', $formData->id)
            );
        } catch(NoSuchCategoryException) {
            return new Response(
                sprintf('Selected category with id %s not found', $formData->categoryId)
            );
        } catch(CannotActivateArticle $e) {
            return new Response(
                sprintf('Can not activate the article: %s', $e->getMessage())
            );
        }

        /** success */
        $url = $this->urlHelper->getAbsoluteUrl(sprintf('/admin/article/%s', $id()));
        
        return new RedirectResponse($url);
    }

    #[Route('/admin/article/delete/{id}', name: 'admin_article_delete', methods: ['POST'])]
    public function delete($id): Response
    {
        try {
            $this->articleService->delete($id);

            $url = $this->urlHelper->getAbsoluteUrl('/admin/article');
            return new RedirectResponse($url);
        } catch (NoSuchArticleException) {
            throw $this->createNotFoundException(
                sprintf('article with id %s not found', $id)
            );
        } catch(InvalidArgumentException $e) {
            return new Response(
                sprintf('Cannot delete article with id %s: %s', $id, $e->getMessage())
            );
        } catch(CouldNotDeleteException $e){
            return new Response(
                sprintf('Cannot delete article with id %s, please try later', $id)
            );
        }
    }
}
