<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Epreuve;
use App\Entity\Eleve;
use App\Entity\Passe;
use App\Repository\EpreuveRepository;
use App\Form\AjoutEpreuveType;
use App\Form\ModifEpreuveType;
use App\Form\ModifNoteType;
use Symfony\Component\Validator\Constraints\DateTime;
class EpreuveController extends AbstractController
{
    /**
     * @Route("/liste_epreuves", name="liste_epreuves")
     */
    public function listeEpreuve(Request $request): Response
    {
        $epreuve = new Epreuve(); 
        $passe = new Passe();    
        $em = $this->getDoctrine();
        $form = $this->createForm(AjoutEpreuveType::class, $epreuve);
        $repoPasse = $em->getRepository(Epreuve::class);
        $repoEpreuve = $em->getRepository(Epreuve::class);
        if ($request->get('supp') != null) {
            $epreuve = $repoEpreuve->find($request->get('supp'));
            if ($epreuve != null) {
                $em->getManager()->remove($epreuve);
                $em->getManager()->flush();
            }
            return $this->redirectToRoute('liste_epreuves');
        }
       $epreuves = $repoEpreuve->findBy(array(), array());
        return $this->render('epreuves/liste_epreuves.html.twig', [
            'epreuve' => $epreuves,
            
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
                $file = $form->get('sujet')->getData();
                $nomModule= $form->get('nomModule')->getData();
                $fileName = date("Y-m-d H:i:s") . "_" . $nomModule . '.' . $file->guessExtension();
               
                $em = $this->getDoctrine()->getManager();
                try {
                
                    $file->move($this->getParameter('sujet_directory'), $fileName); 
                    $em = $this->getDoctrine()->getManager();
                    $epreuve->setSujet($fileName); 
                    $em->persist($epreuve);
                    $em->flush();
               
                } catch (FileException $e) {
                    $this->addFlash('notice', 'Problème fichier inséré');
                }
                
                $this->addFlash('notice', 'Epreuve inséré'); 

            }

            return $this->redirectToRoute('liste_epreuves');
        }
        return $this->render('epreuves/ajout_epreuves.html.twig', [
            'form' => $form->createView() 
        ]);
    }

    /**
     * @Route("/modif_epreuve/{id}", name="modif_epreuve", requirements={"id"="\d+"})
     */
    public function modifEpreuve(int $id, Request $request)
    {
        $em = $this->getDoctrine();
        $repoEpreuve = $em->getRepository(Epreuve::class);
        $epreuve = $repoEpreuve->find($id);
        if ($epreuve == null) {
            $this->addFlash('notice', "Cette épreuve n'existe pas");
            return $this->redirectToRoute('liste_epreuves');
        }
        $form = $this->createForm(ModifEpreuveType::class, $epreuve);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($epreuve);
                $em->flush();
                $this->addFlash('notice', 'Épreuve modifiée');
            }
            return $this->redirectToRoute('liste_epreuves');
        }
        return $this->render('epreuves/modif_epreuves.html.twig', [
            'form' => $form->createView()
        ]);
    }


   /**
     * @Route("/epreuveCours/{id}", name="epreuveCours", requirements={"id"="\d+"})
     */
    public function epreuveCours(EpreuveRepository $epreuveRepository, int $id, Request $request)
    {
        $em = $this->getDoctrine();
        $repoEpreuve = $em->getRepository(Epreuve::class);
        $repoEleve = $em->getRepository(Eleve::class);
        $repoPasse = $em->getRepository(Passe::class);
        $epreuve = $repoEpreuve->findOneby(array('id'=>$id));
      
        $dateActuelle = date("Y-m-d H:i:s"); 
       
        

        if ($epreuve == null) {
            $this->addFlash('notice', "Cette épreuve n'existe pas");
            return $this->redirectToRoute('liste_epreuves');
        }
      
        $dateEpreuve= $epreuve->getDateEpreuve()->format('Y-m-d H:i:s');
        
        $date1 = strtotime($dateActuelle);
      
        $date1 += 900 + 7200; 
        $date2 = strtotime($dateEpreuve) +7200;
        
      

        if ($date1 >= $date2) {
            
            if ($request->get('emarge') != null) {
                $elevePasse = $repoPasse->findOneBy(array('eleve' => $request->get('emarge'), 'epreuve' => $id));
                
                if ($elevePasse != null) {
                    $em = $this->getDoctrine()->getManager();
                    
                    $dateDebutEpreuve = $epreuve->getHeureDebutEpreuve()->format('Y-m-d H:i:s');
                 
                    $finEpreuve =  strtotime($dateDebutEpreuve) + $epreuve->getDuree() * 60 + 300 ;
                  
                    
                   
                   
                    $elevePasse->setDateRenduEpreuve(new \DateTime());
                    $emargementEtudiant = $elevePasse->getDateRenduEpreuve();
                   
                    
                    if($finEpreuve < $emargementEtudiant->getTimeStamp()+7200){
                        
                        $elevePasse->setNote(0.0);
                       
                        $dateFinal = new \DateTime();
                        $dateFinal->setTimestamp($finEpreuve);
                       

                        $elevePasse->setDateRenduEpreuve($dateFinal);
                    }

                    
                    $em->persist($elevePasse);
                    $em->flush();
              
                }
                
            }
            
            $eleves = $epreuveRepository->findEleveDeEpreuve($id);
           
            return $this->render('epreuves/epreuveCours.html.twig', [
                "epreuve" => $dateEpreuve,
                "infoEpreuve" => $epreuve,
                "dateEpreuve" => $dateEpreuve,
                "eleves" => $eleves
                ]);
            
        }

        return $this->render('epreuves/epreuveCours.html.twig', [
           "dateActuelle" => $dateActuelle,
           "infoEpreuve" => $epreuve,
           "dateEpreuve" => $dateEpreuve,
           
        ]);
    }

     /**
     * @Route("/chrono/{minute}/{id}", name="chrono", requirements={"minute"="\d+", "id"="\d+"})
     */
    public function chrono(int $minute, int $id, Request $request): Response
    {
        $em = $this->getDoctrine();
        $repoEpreuve = $em->getRepository(Epreuve::class);
        $epreuve = $repoEpreuve->find($id);
        if ($epreuve == null) {
            $this->addFlash('notice', "Cette épreuve n'existe pas");
            return $this->redirectToRoute('liste_epreuves');
        }
        if ($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            date_default_timezone_set('Europe/Paris');
            $epreuve->setHeureDebutEpreuve(new \DateTime());
            $em->persist($epreuve);
            $em->flush();
           
                 
          }
       
        return $this->render('epreuves/chrono.html.twig', [
            'controller_name' => 'EpreuveController',
        ]);
    }

    /**
     * @Route("/correction_epreuve/{id}", name="correction_epreuve", requirements={"id"="\d+"})
     */
    public function correction_epreuve(int $id, Request $request): Response
    {
        $em = $this->getDoctrine();
        $repoEpreuve = $em->getRepository(Epreuve::class);
        $repoPasse= $em->getRepository(Passe::class);
        $passe = $repoPasse->findBy(['epreuve' => $id],
        );
        
     
        return $this->render('epreuves/correction_epreuve.html.twig', [
            'controller_name' => 'EpreuveController',
        
            'passe'=>$passe,
            
        ]);
    }

    /**
     * @Route("/modifNote/{id}/{idEleve}", name="modifNote", requirements={"id"="\d+", "idEleve"="\d+"})
     */
    public function modif_note(int $id, int $idEleve, Request $request): Response
    {
        $em = $this->getDoctrine();
        $repoPasse= $em->getRepository(Passe::class);
        $passe = $repoPasse->findOneBy(['epreuve' => $id, 'eleve'=>$idEleve],
        );
        $form = $this->createForm(ModifNoteType::class, $passe);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($passe);
                 $em->flush();

            }
            return $this->redirectToRoute('correction_epreuve', array('id' => $id));
        }

        return $this->render('epreuves/modifNote.html.twig', [
            'controller_name' => 'EpreuveController',
            'form' => $form->createView()
            
        ]);
    }
}
