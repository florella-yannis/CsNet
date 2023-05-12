<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Prospect;
use App\Entity\DemandeDevis;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DemandeDevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/voir-mon-dashboard', name: 'show_dashboard', methods: ['GET', 'POST'])]
    public function showClients(EntityManagerInterface $entityManager, DemandeDevisRepository $demandeDevisRepository): Response
    {
        $clients = $entityManager->getRepository(User::class)->findAll();
        $prospects = $entityManager->getRepository(Prospect::class)->findAll();
        $demandesDevis = $demandeDevisRepository->findBy([], ['date' => 'DESC']);

        return $this->render('admin/show_dashboard.html.twig', [
            'clients' => $clients,
            'prospects' => $prospects,
            'demandesDevis' => $demandesDevis,
        ]);
    }

    #[Route('/demande-devis/{id}/modifier-statut', name: 'demande_devis_modifier_statut', methods: ['GET', 'POST'])]
    public function modifierStatut(DemandeDevis $demandeDevis, Request $request, DemandeDevisRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder($demandeDevis)
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'en_cours' => 'En cours de traitement',
                    'traite' => 'Traitée',
                ],
                'label' => 'Statut',
            ])
            ->add('Modifier', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $demandeDevis->setUpdatedAt(new DateTime());

            $repository->save($demandeDevis, true);

            $this->addFlash('success', 'Le statut de la demande de devis a été modifié avec succès.');

            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('admin/demandedevis/modifier_statut.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
