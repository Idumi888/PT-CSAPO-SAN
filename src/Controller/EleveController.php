<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Eleve;
use App\Entity\Passe;
use App\Entity\Epreuve;
use App\Form\AjoutEleveType;
use App\Form\AjoutElevePasseType;
class EleveController extends AbstractController
{
    /**
     * @Route("/eleve", name="eleve")
     */
    public function index(): Response
    {
        return $this->render('eleve/index.html.twig', [
            'controller_name' => 'EleveController',
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
                $eleve->setPhoto($fileName); // Le nom du fichier va être celui généré

                try {
                    
                    $file->move($this->getParameter('profile_directory'), $fileName); // Nous déplaçons lefichier dans le répertoire configuré dans services.yaml
                    $em = $this->getDoctrine()->getManager();
                    $eleve->setPhoto($fileName);
                    $em->persist($eleve);
                    $em->flush();
                    $this->addFlash('notice', 'Fichier inséré');
                } catch (FileException $e) {                // erreur durant l’upload            }
                    $this->addFlash('notice', 'Problème fichier inséré');
                }
                
            }
        }
       

        return $this->render('eleves/ajout_eleves.html.twig', [
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
       

        return $this->render('eleves/elevePasse.html.twig', [
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
}
