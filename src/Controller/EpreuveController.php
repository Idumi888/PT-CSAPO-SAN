<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Epreuve;
use App\Form\AjoutEpreuveType;
use App\Form\ModifEpreuveType;

class EpreuveController extends AbstractController
{
    /**
     * @Route("/liste_epreuves", name="liste_epreuves")
     */
    public function listeEpreuve(Request $request): Response
    {
        $em = $this->getDoctrine();
        $repoEpreuve = $em->getRepository(Epreuve::class);
        if ($request->get('supp') != null) {
            $epreuve = $repoEpreuve->find($request->get('supp'));
            if ($epreuve != null) {
                $em->getManager()->remove($epreuve);
                $em->getManager()->flush();
            }
            return $this->redirectToRoute('epreuve');
        }
        $epreuves = $repoEpreuve->findBy(array(), array('type' => 'ASC'));
        return $this->render('epreuves/liste_epreuve.html.twig', [
            'epreuve' => $epreuves 
        ]);
    }

    /**
     * @Route("/ajout_epreuve", name="ajout_epreuve")
     */
    public function ajoutEpreuve(Request $request)
    {
        $epreuve = new Epreuve(); 
        $form = $this->createForm(AjoutEpreuveType::class, $epreuve);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {


                $em = $this->getDoctrine()->getManager();

                $em->persist($epreuve); 
                $em->flush(); 
                $this->addFlash('notice', 'Epreuve inséré'); 

            }
            return $this->redirectToRoute('ajout_epreuve');
        }
        return $this->render('epreuves/ajout_epreuve.html.twig', [
            'form' => $form->createView() 
        ]);
    }
}
