<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Devis;
use App\Entity\DetailDevis;
use App\Form\DevisFormType;
use App\Form\DetailDevisFormType;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DetailDevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
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
            $devis->newNumberDevis();

            $repository->save($devis, true);

            $this->addFlash('success', "Le devis a été realisé avec succes.");
            return $this->redirectToRoute('create_detail_devis', ['id' => $devis->getId()]);
        }

        return $this->render('admin/devis/form_devis.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/creer-un-devis/{id}', name: 'create_devis_user', methods: ['GET', 'POST'])]
    public function createDevisUser(Request $request, DevisRepository $repository, User $user, EntityManagerInterface $entityManager): Response
    {
        $devis = new Devis();

        $form = $this->createForm(DevisFormType::class, $devis);


        // Autres champs à préremplir avec les informations de l'utilisateur
        $form->get('client')->setData($user->getClient());
        $form->get('lastname')->setData($user->getLastname());
        $form->get('firstname')->setData($user->getFirstname());
        $form->get('society')->setData($user->getCompany());
        $form->get('adress')->setData($user->getAdress());
        $form->get('zipcode')->setData($user->getZipcode());
        $form->get('city')->setData($user->getCity());

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $devis->setCreatedAt(new DateTime());
            $devis->setUpdatedAt(new DateTime());
            $devis->newNumberDevis();

            $devis->setUser($user);

            $repository->save($devis, true);

            $this->addFlash('success', "Le devis a été realisé avec succes.");
            return $this->redirectToRoute('create_detail_devis', ['id' => $devis->getId()]);
        }


        return $this->render('admin/devis/form_devis.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    //----------------------------------------Form detail devis -----------------------------------------//

    #[Route('/creer-un-devis/{id}/details', name: 'create_detail_devis', methods: ['GET', 'POST'])]
    public function createDetailDevis(Request $request, Devis $devis, DetailDevisRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $detailDevis = new DetailDevis();
        $form = $this->createForm(DetailDevisFormType::class, $detailDevis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Stockez les données du formulaire de devis dans la session
            $request->getSession()->set('devis', $devis);

            // Ajouter le détail de devis à la liste des détails du devis
            $devis->addDetailDevi($detailDevis);

            $devis->newNumberDevis();
            $detailDevis->setDevis($devis);
            $detailDevis->updateFinalPrice();
            $repository->save($detailDevis, true);

            return $this->redirectToRoute('create_detail_devis', ['id' => $devis->getId()]);
        }

        $services = $entityManager->getRepository(DetailDevis::class)->findBy(['devis' => $devis]);

        // Calcul du prix final du devis en ajoutant les prix finaux de chaque détail de devis
        $finalprice = 0;
        foreach ($services as $service) {
            $finalprice += $service->getPricetotal();
        }

        return $this->render('admin/devis/form_detail_devis.html.twig', [
            'form' => $form->createView(),
            'services' => $services,
            'finalprice' => $finalprice,
            'devis' => $devis
        ]);
    }

    //----------------------------------------Supprimer un service -----------------------------------------//

    #[Route('/supprimer-un-service/{id}', name: 'delete_service', methods: ['GET'])]
    public function deleteService(DetailDevis $detailDevis, Devis $devis, DetailDevisRepository $repository): Response
    {

        $repository->remove($detailDevis, true);


        $this->addFlash('success', "Le service a bien été supprimé");
        return $this->redirectToRoute('create_detail_devis', ['id' => $devis->getId()]);
    }

    //---------------------------------------- Récapitulatif du devis -----------------------------------------//

    #[Route('/recapitulatif-du-devis/{id}', name: 'recap_devis',  methods: ['GET'])]
    public function recapDevis(Request $request, DevisRepository $devisRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->attributes->get('id');

        // Récupérez le devis à partir de l'identifiant
        $devis = $devisRepository->find($id);

        // Vérifiez si le devis existe
        if (!$devis) {
            throw $this->createNotFoundException('Le devis avec l\'identifiant ' . $id . ' n\'existe pas');
        }

        // Récupérez les détails du devis à partir de l'objet $devis
        $services = $entityManager->getRepository(DetailDevis::class)->findBy(['devis' => $devis]);

        // Calculez le prix total
        $finalprice = 0;
        foreach ($services as $service) {
            $finalprice += $service->getPricetotal();
        }


        // Affichez les données sur la page Twig de confirmation de devis
        return $this->render('admin/devis/recap_devis.html.twig', [
            'services' => $services,
            'devis' => $devis,
            'finalprice' => $finalprice

        ]);
    }


    //----------------------------------------show l'ensemble des devis -----------------------------------------//

    #[Route('/voir-mes-devis', name: 'show_devis', methods: ['GET', 'POST'])]
    public function showClients(EntityManagerInterface $entityManager, DevisRepository $demandeDevisRepository): Response
    {
        $devis = $entityManager->getRepository(Devis::class)->findAll();

        return $this->render('admin/devis/show_devis.html.twig', [
            'devis' => $devis,
        ]);
    }

    //-------------------------------------------suprimer un devis---------------------------------------------------//
    #[Route('/supprimer-un-devis/{id}', name: 'hard_delete_devis', methods: ['GET'])]
    public function hardDeleteDevis(Devis $devis, DevisRepository $repository,  EntityManagerInterface $entityManager): Response
    {
        // Supprimer les détails de devis associés
        foreach ($devis->getDetaildevis() as $detailDevis) {
            $devis->removeDetailDevi($detailDevis);
            // Supprimer le détail de devis
            $entityManager->remove($detailDevis, true);
        }
        $repository->remove($devis, true);

        $this->addFlash('success', "Le devis a bien été supprimé définitivement de la base.");
        return $this->redirectToRoute('show_devis');
    }
}
