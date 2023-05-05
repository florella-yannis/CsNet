<?php

namespace App\Controller;

use App\Entity\Prospect;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/voir-mon-dashboard', name: 'show_dashboard', methods: ['GET', 'POST'])]
    public function showClients( EntityManagerInterface $entityManager): Response
    {
        $clients = $entityManager->getRepository(User::class)->findAll();
        $prospects = $entityManager->getRepository(Prospect::class)->findAll();

        return $this->render('admin/show_dashboard.html.twig',[
            'clients'=> $clients,
            'prospects'=>$prospects
        ]);
    }
}
