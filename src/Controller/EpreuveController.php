<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Epreuve;

use App\Form\AjoutEpreuveType;
use App\Form\ModifEpreuveType;
use Symfony\Component\Validator\Constraints\DateTime;
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
                $file = $form->get('sujet')->getData();
                $nom = $form->get('nomModule')->getData();
                $fileName = date("d-m-Y_H:i") ."_". $nom . '.' . $file->guessExtension();
                $epreuve->setSujet($fileName);
                try {
                    
                    $file->move($this->getParameter('sujet_directory'), $fileName); // Nous déplaçons lefichier dans le répertoire configuré dans services.yaml
                  
                    $epreuve->setSujet($fileName);
                    $em->persist($epreuve);
                    $em->flush();
                    $this->addFlash('notice', 'Epreuve inséré'); 
                } catch (FileException $e) {                // erreur durant l’upload            
                    $this->addFlash('notice', 'Problème fichier inséré');
                }


               
                

            }
            return $this->redirectToRoute('liste_epreuves');}
        $epreuves = $repoEpreuve->findBy(array(), array());
        return $this->render('epreuves/liste_epreuves.html.twig', [
            'epreuve' => $epreuves,
            'form'=>$form->createView()
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

   /**
     * @Route("/epreuveCours/{id}", name="epreuveCours", requirements={"id"="\d+"})
     */
    public function epreuveCours(int $id, Request $request)
    {
        $em = $this->getDoctrine();
        $repoEpreuve = $em->getRepository(Epreuve::class);
        $epreuve = $repoEpreuve->findOneby(array('id'=>$id));
        date_default_timezone_set('Europe/Paris');
        $dateActuelle = date("Y-m-d H:i:s"); 
       
        

        if ($epreuve == null) {
            $this->addFlash('notice', "Cette épreuve n'existe pas");
            return $this->redirectToRoute('liste_epreuves');
        }
        $dateEpreuve= $epreuve->getDateEpreuve()->format('Y-m-d H:i:s');
        $date1 = strtotime($dateActuelle);
        $date1 += 900; 
        $date2 = strtotime($dateEpreuve);

        if ($date1 >= $date2) {
            
            return $this->render('epreuves/epreuveCours.html.twig', [
                "epreuve" => $dateEpreuve,
                
                "dateEpreuve" => $dateEpreuve,
                ]);
            
        }
        
          
        return $this->render('epreuves/epreuveCours.html.twig', [
           "dateActuelle" => $dateActuelle,
           "dateEpreuve" => $dateEpreuve,
        ]);
    }
}
