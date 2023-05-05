<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/voir-mes-clients', name: 'show_clients', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        
        return $this->render('admin/show_clients.html.twig');
    }
}
