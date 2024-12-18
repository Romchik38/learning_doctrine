<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController{

    #[Route('/', name: 'homepage_index', methods: ['GET', 'HEAD'])]
    public function index(): Response {
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'controller_template' => 'home/index.html.twig'
        ]);
    }
}