<?php

namespace App\Controller;

use DateTime;
use App\Entity\Devis;
use App\Entity\DetailDevis;
use App\Form\DevisFormType;
use Doctrine\ORM\EntityManager;
use App\Form\DetailDevisFormType;
use App\Repository\DetailDevisRepository;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DevisController extends AbstractController
{
    #[Route('/creer-un-devis', name: 'create_devis', methods: ['GET', 'POST'])]
    public function createDevis(Request $request, DevisRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $devis = new Devis();

        $form = $this->createForm(DevisFormType::class, $devis)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devis->setCreatedAt(new DateTime());
            $devis->setUpdatedAt(new DateTime());

            $currentYear = date('Y');
            $nextDevisNumber = $repository->getNextDevisNumber($currentYear);
            $devis->setNumberDevis($currentYear, $nextDevisNumber);

            foreach ($devis->getDetaildevis() as $detailDevis) {
                $entityManager->persist($detailDevis);
                $entityManager->flush();
            }

            $repository->save($devis, true);

            $this->addFlash('succes', "Le devis a été realisé avec succes.");
            return $this->redirectToRoute('create_detail_devis', ['id' => $devis->getId()]);

        }

        return $this->render('admin/devis/form_devis.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/creer-un-devis/{id}/details', name: 'create_detail_devis', methods: ['GET', 'POST'])]
    public function createDetailDevis(Request $request, Devis $devis, DetailDevisRepository $repository,EntityManagerInterface $entityManager): Response
    {
        $detailDevis = new DetailDevis();
        $form = $this->createForm(DetailDevisFormType::class, $detailDevis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailDevis->setDevis($devis);
            $detailDevis->updateFinalPrice();
            $repository->save($detailDevis, true);

            return $this->redirectToRoute('create_detail_devis', ['id' => $devis->getId()]);
        }

        $services = $entityManager->getRepository(DetailDevis::class)->findAll();

        return $this->render('admin/devis/form_detail_devis.html.twig', [
            'form' => $form->createView(),
            'services' => $services,
        ]);
    }

    #[Route('/supprimer-un-service/{id}', name: 'delete_service', methods: ['GET'])]
    public function deleteService(DetailDevis $detailDevis, Devis $devis, DetailDevisRepository $repository): Response
    {
        
        $repository->remove($detailDevis, true);


        $this->addFlash('success', "Le service a bien été supprimé");
        return $this->redirectToRoute('create_detail_devis', ['id' => $devis->getId()]);
    }

    
}
