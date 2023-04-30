<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name:'show_home', methods:['GET'])]
    public function showHome(): Response
    {
        return $this->render('default/show_home.html.twig');
    }

    #[Route('/prestation-entretien', name:'show_entretien', methods:['GET'])]
    public function showEntretien(): Response
    {
        return $this->render('default/show_entretien.html.twig');
    }

    #[Route('/prestation-espace-vert', name:'show_espace_vert', methods:['GET'])]
    public function showEspaceVert(): Response
    {
        return $this->render('default/show_espace_vert.html.twig');
    }
}
