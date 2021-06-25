<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Eleve;
use App\Entity\Concerne;
use App\Entity\Passe;
use App\Entity\Epreuve;
use App\Form\AjoutEleveType;
use App\Form\ModifEleveType;
use App\Form\AjoutElevePasseType;
class EleveController extends AbstractController
{
    /**
     * @Route("/liste_eleves", name="liste_eleves")
     */
    public function liste_eleves(Request $request): Response
    {
        $em = $this->getDoctrine();
        $repoEleve = $em->getRepository(Eleve::class);
        if ($request->get('supp') != null) {
            $eleve = $repoEleve->find($request->get('supp'));
            if ($eleve != null) {
                $em->getManager()->remove($eleve);
                $em->getManager()->flush();
            }
            return $this->redirectToRoute('liste_eleves');
        }
        $eleves = $repoEleve->findBy(array(), array('nom' => 'ASC'));
        return $this->render('eleve/liste_eleves.html.twig', [
            'eleve' => $eleves 
        ]);
    }


    /**
     * @Route("/ajout_eleve", name="ajout_eleve")
     */
    public function ajoutEleve(Request $request)
    {
        $eleve = new Eleve(); 
        $form = $this->createForm(AjoutEleveType::class, $eleve);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('photo')->getData();
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                $eleve->setPhoto($fileName); 

                try {
                    
                    $file->move($this->getParameter('profile_directory'), $fileName); 
                    $em = $this->getDoctrine()->getManager();
                    $eleve->setPhoto($fileName);
                    $em->persist($eleve);
                    $em->flush();
                    $this->addFlash('notice', 'Élève inséré');
                } catch (FileException $e) {
                    $this->addFlash('notice', 'Problème fichier inséré');
                }
                return $this->redirectToRoute('liste_eleves');    
            }
        }
       

        return $this->render('eleve/ajout_eleve.html.twig', [
            'form' => $form->createView() 
        ]);
    }
    

    /**
     * @Route("/modif_eleve/{id}", name="modif_eleve", requirements={"id"="\d+"})
     */
    public function modifEleve(int $id, Request $request)
    {
        $em = $this->getDoctrine();
        $repoEleve = $em->getRepository(Eleve::class);
        $eleve = $repoEleve->find($id);
        if ($eleve == null) {
            $this->addFlash('notice', "Cet élève n'existe pas");
            return $this->redirectToRoute('liste_eleves');
        }
        $form = $this->createForm(ModifEleveType::class, $eleve);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('photo')->getData();
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                $eleve->setPhoto($fileName);


                try {
                    $file->move($this->getParameter('profile_directory'), $fileName);
                    $em = $this->getDoctrine()->getManager();
                    $eleve->setPhoto($fileName);
                    $em->persist($eleve);
                    $em->flush();
                    $this->addFlash('notice', 'Élève modifié');
                } catch (FileException $e) {
                    $this->addFlash('notice', 'Problème fichier inséré');
                }
                return $this->redirectToRoute('liste_eleves');
            }
        }
        return $this->render('eleve/modif_eleve.html.twig', [
            'form' => $form->createView()
        ]);
    }


     /**
     * @Route("/elevePasse", name="elevePasse")
     */
    public function elevePasse(Request $request)
    {
        $passe = new Passe(); 
        $form = $this->createForm(AjoutElevePasseType::class, $passe);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($passe);
                $em->flush();
                
            }
        }
       

        return $this->render('eleve/elevePasse.html.twig', [
            'form' =>$form->createView(),
        ]);
    }            
     /**
     * * @return string
     *
     * */
    private function generateUniqueFileName()
    {
        return md5(uniqid()); // Génère un md5 sur un identifiant généré aléatoirement
    }


    /** 
    * @Route("/liste_eleves_api", name="liste_eleves_api") 
    */ 
    public function ajaxListeEleve(Request $request) {  
        $eleves = $this->getDoctrine() 
           ->getRepository(Eleve::class) 
           ->findAll();  
           
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
           $jsonData = array();  
           $idx = 0;  
           foreach($eleves as $eleve) {  
              $temp = array(
                 'nom' => $eleve->getNom(),  
                 'prenom' => $eleve->getPrenom(),  
              );   
              $jsonData[$idx++] = $temp;  
           } 
           return new JsonResponse($jsonData); 
        } else { 
           return $this->render('eleve/ajax_liste_eleves.html.twig'); 
        } 
     }  
















}
