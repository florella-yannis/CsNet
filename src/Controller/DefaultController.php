<?php

namespace App\Controller;

use DateTime;
use App\Entity\Prospect;
use App\Form\ProspectFormType;
use Symfony\Component\Mime\Email;
use App\Repository\ProspectRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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

    #[Route('/contact', name:'show_contact', methods:['GET','POST'])]
    public function showContact(Request $request, ProspectRepository $repository, MailerInterface $mailer): Response
    {
        $prospect = new Prospect();

        $form = $this->createForm(ProspectFormType::class,$prospect)
        ->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $prospect->setCreatedAt(new DateTime());
            $prospect->setUpdatedAt(new DateTime());

            $repository->save($prospect, true);

            //email
            $email = (new TemplatedEmail())
            ->from($prospect->getEmail())
            ->to('contact@csnet.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($prospect->getSubject())
            ->text($prospect->getMessage())
            ->htmlTemplate('default/email/email_contact.html.twig')

            ->context([
                'prospect' => $prospect,
            ]);

        $mailer->send($email);

            $this->addFlash('success', "Votre demande a été prise en compte");
            return $this->redirectToRoute('show_home');
        }

        return $this->render('default/show_contact.html.twig', [
            'form' => $form->createView()
            ]);
    }

    



    // parti du footer

    #[Route('/mentions-legales', name: 'mentions_legales',methods:['GET'])]
    public function mentionsLegals(): Response
    {
        return $this->render('divers/mentions_legales.html.twig');
    }

    #[Route('/conditions-generales', name: 'conditions_generales',methods:['GET'])]
    public function conditionsGenerales(): Response
    {
        return $this->render('divers/conditions_generales.html.twig');
    }

    #[Route('/plan-du-site', name: 'plan_site',methods:['GET'])]
    public function planSite(): Response
    {
        return $this->render('divers/plan_site.html.twig');
    }

}
