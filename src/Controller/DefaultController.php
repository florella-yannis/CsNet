<?php

namespace App\Controller;

use DateTime;
use App\Entity\Prospect;
use App\Form\ProspectFormType;
use App\Repository\ProspectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/contact', name:'show_contact', methods:['GET'])]
    public function showContact(Request $request, ProspectRepository $repository): Response
    {
        $prospect = new Prospect();

        $form = $this->createForm(ProspectFormType::class,$prospect)
        ->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $prospect->setCreatedAt(new DateTime());
            $prospect->setUpdatedAt(new DateTime());

            $repository->save($prospect, true);

            $this->addFlash('succes', "Le produit est en ligne avec succès.");
            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('default/show_contact.html.twig', [
            'form' => $form->createView()
            ]);
    }
}
