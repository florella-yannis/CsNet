<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Devis;
use App\Entity\DetailDevis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    #[Route('/{id}/pdf', name: 'app_devis_pdf',  methods: ['GET'])]
    public function createPdf(Devis $devis, EntityManagerInterface $entityManager): Response
    {
        $dompdf = new Dompdf();

        $services = $entityManager->getRepository(DetailDevis::class)->findBy(['devis' => $devis]);

        $html = $this->renderView('pdf/pdf.html.twig', [
            'devis' => $devis,
            'services' => $services
        ]);

        //  dd($html);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $output = $dompdf->output();
        $filename = 'devis_' . $devis->getNumberdevis() . '.pdf';
        $file = $this->getParameter('kernel.project_dir') . '/public/' . $filename;

        file_put_contents($file, $output);
        //afichage du pdf 
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;

        // return $this->redirectToRoute('recap_devis', ['id' => $devis->getId()]);
    }
}
