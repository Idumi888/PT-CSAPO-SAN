<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Epreuve;
use App\Form\AjoutEpreuveType;
use App\Form\ModifEpreuveType;
use Symfony\Component\HttpFoundation\Request;

class EpreuveController extends AbstractController
{
    /**
     * @Route("/liste_epreuves", name="liste_epreuves")
     */
    public function listeEpreuve(Request $request): Response
    {
        $epreuve = new Epreuve(); 
        $em = $this->getDoctrine();
        $form = $this->createForm(AjoutEpreuveType::class, $epreuve);
        $repoEpreuve = $em->getRepository(Epreuve::class);
        if ($request->get('supp') != null) {
            $epreuve = $repoEpreuve->find($request->get('supp'));
            if ($epreuve != null) {
                $em->getManager()->remove($epreuve);
                $em->getManager()->flush();
            }
            return $this->redirectToRoute('liste_epreuves');
        }
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {


                $em = $this->getDoctrine()->getManager();

                $em->persist($epreuve); 
                $em->flush(); 
                $this->addFlash('notice', 'Epreuve inséré'); 

            }
            return $this->redirectToRoute('liste_epreuves');}
        $epreuves = $repoEpreuve->findBy(array());
        return $this->render('epreuves/liste_epreuves.html.twig', [
            'epreuve' => $epreuves,
            'form' => $form->createView() 
        ]);
    }

   
}
