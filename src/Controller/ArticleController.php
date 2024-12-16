<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/new', name: 'article_new', methods: ['GET', 'HEAD'])]
    public function new(): Response
    {
        return $this->render('article/new.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
}
