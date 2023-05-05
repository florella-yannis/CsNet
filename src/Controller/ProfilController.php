<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use Symfony\Component\Form\FormError;
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
    public function showProfil(Request $request, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): Response
    {

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        return $this->render('profil/show_profil.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/modifier-mon-profil/{id}', name: 'edit_profil', methods: ['GET', 'POST'])]
    public function editProfil(Request $request, User $user, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): Response
    {

        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'utilisateur connecté
        $currentUser = $this->getUser();

        // Vérifier si l'utilisateur existe
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas.');
        }

        // Vérifier si l'utilisateur connecté est autorisé à modifier cet utilisateur
        if ($currentUser !== $user) {
            return $this->redirectToRoute('show_home');
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

    #[Route('/changer-mot-de-passe', name: 'change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): Response
{
    //probleme de securité a mettre a jour , UserPasswordEncoderInterface ne fonctionne pas
    // Vérifier si l'utilisateur est connecté
    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Créer le formulaire de changement de mot de passe
    $form = $this->createForm(ChangePasswordFormType::class, $user);

    // Traiter la soumission du formulaire
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier que le mot de passe actuel est correct
        $currentPassword = $form->get('current_password')->getData();
        if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
            $form->get('current_password')->addError(new FormError('Le mot de passe actuel est incorrect.'));
        } else {
            // Récupérer le nouveau mot de passe et le hasher
            $newPassword = $form->get('new_password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
             $user->setNewpassword($passwordHasher->hashPassword($user, $user->getNewpassword()));

            // Mettre à jour le mot de passe de l'utilisateur
            $user->setPassword($hashedPassword);
            $repository->upgradePassword($user, $hashedPassword);

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
            return $this->redirectToRoute('show_profil');
        }
    }

    return $this->render('profil/change_password.html.twig', [
        'form' => $form->createView(),
    ]);
}

    
}


