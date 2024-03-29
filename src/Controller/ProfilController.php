<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Devis;
use App\Form\UserFormType;
use App\Entity\DemandeDevis;
use App\Form\DemandeDevisFormType;
use App\Form\EditPasswordFormType;
use App\Repository\UserRepository;
use App\Repository\DevisRepository;
use Symfony\Component\Form\FormError;
use App\Repository\DemandeDevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/voir-mon-profil', name: 'show_profil', methods: ['GET', 'POST'])]
    public function showProfil(Request $request, DevisRepository $devis, DemandeDevisRepository $demandeDevisRepository): Response
    {

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        $demandesDevis = $demandeDevisRepository->findBy(['user' => $user]);
        $devis = $devis->findBy(['user' => $user]);

        return $this->render('profil/show_profil.html.twig', [
            'user' => $user,
            'demandesDevis' => $demandesDevis,
            'devis'=>$devis
        ]);
    }

    #[Route('/modifier-mon-profil/{id}', name: 'edit_profil', methods: ['GET', 'POST'])]
    public function editProfil(Request $request, User $user, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): Response
    {

        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si l'utilisateur existe
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas.');
        }


        $form = $this->createForm(UserFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new DateTime());

            // Sauvegarde de l'utilisateur modifié
            $repository->save($user, true);

            $this->addFlash('success', 'Vos modifications ont été enregistrées.');

            return $this->redirectToRoute('show_profil');
        }

        return $this->render('profil/edit_profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/changer-mot-de-passe/{id}', name: 'edit_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, User $user, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): Response
    {
        //probleme de securité a mettre a jour , UserPasswordEncoderInterface ne fonctionne
        // Vérifier si l'utilisateur est connecté
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute('app_login');
        }

        // Créer le formulaire de changement de mot de passe
        $form = $this->createForm(EditPasswordFormType::class, $user);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier que le mot de passe actuel est correct
            $currentPassword = $form->get('currentpassword')->getData();
            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $form->get('currentpassword')->addError(new FormError('Le mot de passe actuel est incorrect.'));
            } else {
                // Récupérer le nouveau mot de passe et le hasher
                $newPassword = $form->get('newpassword')->getData();
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                // le hash dans newpassword est null
                $user->setNewpassword(null);

                // Mettre à jour le mot de passe de l'utilisateur
                $user->setPassword($hashedPassword);
                
                $repository->save($user, true);

                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
                return $this->redirectToRoute('show_profil');
            }
        }

        return $this->render('profil/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //-------------------------------------------Demande de devis -------------------------------//
    #[Route('/demande-de-devis', name: 'ask_demande_devis', methods: ['GET', 'POST'])]
    public function askDemandeDevis(Request $request, DemandeDevisRepository $repository): Response
    {

        $demandeDevis = new DemandeDevis();

        $form = $this->createForm(DemandeDevisFormType::class, $demandeDevis)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $demandeDevis->setCreatedAt(new DateTime());
            $demandeDevis->setUpdatedAt(new DateTime());
            $demandeDevis->setStatut('en cours de traitement');

            $user = $this->getUser();
            if ($user) {
                $demandeDevis->setUser($user);
            }

            $repository->save($demandeDevis, true);

            $this->addFlash('success', "Votre demande de devis a été prise en compte.");
            return $this->redirectToRoute('show_profil');
        }

        return $this->render('profil/ask_demande_devis.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
